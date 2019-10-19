<?php

require_once("env.php");

// Set the headers of the HTTP responses of the API
header("Content-Type: application/json; charset=UTF-8");  // Response type is JSON
header("Access-Control-Allow-Origin: *");                 // CORS management
header("Access-Control-Max-Age: 3600");                   // Pre-flight response is cached 1 hour
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Initialize the database connection
$db = new PDO(
  "mysql:host=" . getenv("host") . ";dbname=" . getenv("dbname"),
  getenv("username"),
  getenv("password")
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Utility functions

/**
 * Stop the execution and return an error.
 * @param int $code an HTTP status error code
 * @param string $message the error message to return
 */
function exitError($code, $message) {
  http_response_code($code);
  $res = ["error" => $message];
  $jsonRes = json_encode($res);
  exit($jsonRes);
}

/**
 * Return a cryptographically secure token.
 * @return string a token
 */
function generateToken($size=32) {
  //base 64
  $dict = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
  $token = "";
  for ($i = 0; $i < $size; $i++)
    $token .= $dict{random_int(0, strlen($dict)-1)};
  return $token;
}

/**
 * Stop the execution if the user didn't provide a valid token, continue otherwise.
 * @param string $token the token provided by the user
 * @param PDO $db the database connection
 */
function authenticate($token, $db) {
  $sql = "SELECT * FROM `User` WHERE token = :token";
  $stmt = $db->prepare();
  $stmt->execute([":token" => $token]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  
  // Guard clauses
  if (!isset($user))
    exitError(401, "Inexistant token.");
  
  $tokenExpiration = DateTime::createFromFormat("Y-m-d H:i:s", $user["token_expiration"]);
  if ($tokenExpiration < new DateTime("now"))
    exitError(401, "The access token expired.");
}

/**
 * Check if an username already exists in the database.
 * @param string $username the username to be checked
 * @param PDO $db the database connection
 * @return true iff username exists
 */
function usernameExists($username, $db) {
  $sql = "SELECT * FROM `User` WHERE username = :username";
  $stmt = $db->prepare($sql);
  $stmt->execute([":username" => $username]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);
  return $user !== false;
}