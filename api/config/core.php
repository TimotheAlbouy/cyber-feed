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
 * @param int $code
 *     an HTTP status error code
 * @param string $message
 *     the error message to return
 */
function exitError($code, $message) {
  http_response_code($code);
  $res = ["error" => $message];
  $jsonRes = json_encode($res);
  exit($jsonRes);
}

/**
 * Return a cryptographically secure token.
 * @return string
 *     the generated token
 */
function generateToken($size=32) {
  //base 64
  $dict = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_";
  $token = "";
  for ($i = 0; $i < $size; $i++) {
    $randInd = random_int(0, strlen($dict)-1);
    $token .= $dict{$randInd};
  }
  return $token;
}

/**
 * Stop the execution if the user didn't provide a valid token, return the data otherwise.
 * @param string $token
 *     the token provided by the user
 * @param PDO $db
 *     the database connection
 * @return array
 *     an associative array containing the user's information
 */
function authenticate($token, $db) {
  if (!isset($token))
    exitError(401, "No token provided.");

  $user = getUserByToken($token, $db);
  if (!$user)
    exitError(401, "The token does not exist.");
  
  $tokenExpiration = DateTime::createFromFormat("Y-m-d H:i:s", $user["token_expiration"]);
  if ($tokenExpiration < new DateTime("now"))
    exitError(401, "Expired access token.");

  return $user;
}

/**
 * Give the user identified by the username given in parameter.
 * @param string $username
 *     the username of the user
 * @param PDO $db
 *     the database connection
 * @return bool|array
 *     - an associative array containing the user info
 *     - false iff the username does not exist
 */
function getUserById($username, $db) {
  $sql = "SELECT username, token_hash, token_expiration FROM `User` WHERE username = :username";
  $stmt = $db->prepare($sql);
  $stmt->execute([":username" => $username]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Give the user identified by the token given in parameter.
 * @param string $token
 *     the token of the user
 * @param PDO $db
 *     the database connection
 * @return bool|array
 *     - an associative array containing the user info
 *     - false iff the token does not exist
 */
function getUserByToken($token, $db) {
  $tokenHash = hash("sha256", $token);
  $sql = "SELECT * FROM `User` WHERE token_hash = :token_hash";
  $stmt = $db->prepare($sql);
  $stmt->execute([":token_hash" => $tokenHash]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}