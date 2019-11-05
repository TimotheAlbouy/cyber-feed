<?php

require_once("Model.php");

/**
 * Model class of the FeedUser database table.
 */
class FeedUser extends Model {

  /**
   * Constructor.
   */
  public function __construct($props=null) {
    $this->props = [
      "feed_id" => null,
      "username" => null
    ];
    if (isset($props))
      $this->setProps($props);
  }

  /**
   * Create the feed user-association in the database.
   * @return FeedUser
   *     the current User object
   */
  public function create() {
    try {
      $db = Database::getInstance();
      $sql = "INSERT INTO `FeedUser` (feed_id, username) VALUES (:feed_id, :username)";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "feed_id" => $this->feed_id,
        "username" => $this->username
      ]);
    } catch (PDOException $e) {
      exitError(500, "Internal error.".$e->getMessage());
    }
  }
  
  /**
   * Check if the feed-user association already exists in the database.
   * @return bool
   *     true iff the feed-user association already exists
   */
  public function exists() {
    try {
      $db = Database::getInstance();
      $sql = "SELECT * FROM `FeedUser` WHERE feed_id = :feed_id AND username = :username";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "feed_id" => $this->feed_id,
        "username" => $this->username
      ]);
      return $stmt->rowCount() !== 0;
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

  /**
   * Give the list of feeds belonging to the user whose username is given in parameter.
   * @param string $username
   *     the username of the user
   * @return array
   *     the retrieved list of FeedUser objects
   */
  public static function findByUsername($username) {
    try {
      $db = Database::getInstance();
      $sql = "SELECT id, url FROM `FeedUser`, `Feed` WHERE id = feed_id AND username = :username";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "username" => $username
      ]);

      if ($stmt->rowCount() === 0)
        return [];

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

}