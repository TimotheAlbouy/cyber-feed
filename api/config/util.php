<?php

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