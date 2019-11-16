<?php

require_once("Model.php");

/**
 * Model class of the User database table.
 */
class User extends Model {

  /**
   * Constructor.
   */
  public function __construct($props=null) {
    $this->props = [
      "username" => null,
      "password_hash" => null,
      "is_admin" => null,
      "token" => null,
      "token_expiration" => null
    ];
    if (isset($props))
      $this->setProps($props);
  }

  /**
   * Give the user identified by the username given in parameter.
   * @param string $id
   *     the username of the user
   * @return User
   *     the retrieved User object
   */
  public static function findById($id) {
    try {
      $db = Database::getInstance();
      $sql = "SELECT * FROM `User` WHERE username = :username";
      $stmt = $db->prepare($sql);
      $stmt->execute(["username" => $id]);
      
      if ($stmt->rowCount() === 0)
        return null;
      
      return new User($stmt->fetch(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

  /**
   * Stop the execution if the user didn't provide a valid token, return the user otherwise.
   * @param string $token
   *     the access token
   * @param bool $isAdmin
   *     true iff we add an admin check on the token
   * @return User
   *     the retrieved User object
   */
  public static function authenticate($token, $isAdmin=false) {
    try {
      $db = Database::getInstance();
      $sql = "SELECT * FROM `User` WHERE token = :token";
      if ($isAdmin) $sql .= " AND is_admin = TRUE";
      $stmt = $db->prepare($sql);
      $stmt->execute(["token" => $token]);

      if ($stmt->rowCount() === 0)
        exitError(401, "The token does not exist.");
      
      $user = new User($stmt->fetch(PDO::FETCH_ASSOC));
      
      $expirationDate = DateTime::createFromFormat("Y-m-d H:i:s", $user->token_expiration);
      if ($expirationDate < new DateTime("now"))
        exitError(401, "Expired access token.");

      return $user;
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

  /**
   * Create the user in the database.
   * @return User
   *     the current User object
   */
  public function create() {
    try {
      $db = Database::getInstance();
      $sql = "INSERT INTO `User` (username, password_hash, token, token_expiration)
              VALUES (:username, :password_hash, :token, :token_expiration)";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "username" => $this->username,
        "password_hash" => $this->password_hash,
        "token" => $this->token,
        "token_expiration" => $this->token_expiration
      ]);
      $this->is_admin = false;
      return $this;
    } catch (PDOException $e) {
      exitError(500, "Internal error.".$e->getMessage());
    }
  }

  /**
   * Update the user in the database with a new token and expiration date.
   * @return User
   *     the current User object
   */
  public function updateToken() {
    try {
      $db = Database::getInstance();
      $expirationDate = new DateTime("now");
      $expirationDate->add(new DateInterval("P1D"));
      $this->token_expiration = $expirationDate->format("Y-m-d H:i:s");
      $this->token = generateToken();

      $sql = "UPDATE `User`
              SET token = :token,
                  token_expiration = :token_expiration
              WHERE username = :username";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "username" => $this->username,
        "token" => $this->token,
        "token_expiration" => $this->token_expiration
      ]);
      return $this;
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

  /**
   * Give all the users of the database.
   * @return array
   *     the list of retrieved users
   */
  public static function all() {
    try {
      $db = Database::getInstance();
      $sql = "SELECT username, is_admin, token, token_expiration FROM `User`";
      $stmt = $db->prepare($sql);
      $stmt->execute();
      
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

  /**
   * Remove the users from the database.
   */
  public function delete() {
    try {
      $db = Database::getInstance();
      $sql = "DELETE FROM `User` WHERE username = :username";
      $stmt = $db->prepare($sql);
      $stmt->execute(["username" => $this->username]);
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

}