<?php

require_once("env.php");
require_once("util.php");

/**
 * Class always providing one unique database connection.
 */
class Database {

  // the connection instance
  private static $db;

  /**
   * Create the database connection instance.
   */
  private static function createInstance() {
    try {
      self::$db = new PDO(
        "mysql:host=" . getenv("host") . ";dbname=" . getenv("dbname"),
        getenv("username"),
        getenv("password")
      );
      self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      exitError(500, "Internal error.");
    }
  }

  /**
   * Give the database connection instance.
   */
  public static function getInstance() {
    if (!self::$db)
    self::createInstance();
    return self::$db;
  }

}