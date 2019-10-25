<?php

require_once(__DIR__ . "/../config/Database.php");
require_once(__DIR__ . "/../config/util.php");

/**
 * Parent abstract class for the models of the database. 
 */
abstract class Model {

  // the properties of the model
  protected $props;

  /**
   * Magic getter.
   * @param string $key
   *     the name of the property to retrieve
   */
  public function __get($key) {
    return $this->props[$key];
  }

  /**
   * Magic setter.
   * @param string $key
   *     the name of the property to set
   * @param string $value
   *     the value to assign
   */
  public function __set($key, $value) {
    $this->props[$key] = $value;
  }

  /**
   * Set the properties of the model.
   * @param array $newProps
   *     the new properties of the model
   */
  public function setProps($newProps) {
    foreach ($this->props as $key => $value) {
      if (!isset($newProps[$key]))
        exitError(500, "Setting new model properties failed.");
    }
    $this->props = $newProps;
  }

}