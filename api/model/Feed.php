<?php

require_once("Model.php");

/**
 * Model class of the Feed database table.
 */
class Feed extends Model {

  /**
   * Constructor.
   */
  public function __construct($props=null) {
    $this->props = [
      "id" => null,
      "url" => null
    ];
    if (isset($props))
      $this->setProps($props);
  }

  /**
   * Create the feed in the database.
   * @return Feed
   *     the current Feed object
   */
  public function create() {
    try {
      $db = Database::getInstance();
      $sql = "INSERT INTO `Feed` (url) VALUES (:url)";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "url" => $this->url
      ]);
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }
  
  /**
   * Give the feed identified by the URL given in parameter.
   * @param string $url
   *     the URL of the feed
   * @return Feed
   *     the retrieved Feed object
   */
  public static function findByUrl($url) {
    try {
      $db = Database::getInstance();
      $sql = "SELECT * FROM `Feed` WHERE url = :url";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "url" => $url
      ]);

      if ($stmt->rowCount() === 0)
        return null;

      return new Feed($stmt->fetch(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

   /**
   * Check if the feed already exists in the database.
   * @return bool
   *     true iff the feed already exists
   */
  public function exists() {
    try {
      $db = Database::getInstance();
      $sql = "SELECT * FROM `Feed` WHERE url = :url";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "url" => $this->url
      ]);
      return $stmt->rowCount() !== 0;
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

}