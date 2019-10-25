<?php

require_once(__DIR__ . "/../config/Database.php");
require_once("Model.php");

class Feed extends Model {

  public function __construct() {
    $this->props = [
      "url" => null
    ];
    if (isset($props))
      $this->setProps($props);
  }

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
  
  public function exists() {
    try {
      $db = Database::getInstance();
      $sql = "SELECT * FROM `Feed` WHERE url = :url";
      $stmt = $db->prepare($sql);
      $stmt->execute([
        "url" => $this->url
      ]);
      return $stmt->rowCount() == 0;
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

}