<?php

require_once("database/database.php");

<<<<<<< HEAD
header("Access-Control-Allow-Origin: *");

header('Content-Type: application/json');
=======
header("Access-Control-Allow-Origin: http://localhost/cyber-feed/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
>>>>>>> 315044b1bd806fac85c3eba51601190ca12d7a96

function exitError($code, $message) {
  http_response_code($code);
  $res = ["error" => $message];
  $jsonRes = json_encode($res);
  exit($jsonRes);
}