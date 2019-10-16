<?php

require_once("database/database.php");

header("Access-Control-Allow-Origin: *");

header('Content-Type: application/json');

function exitError($code, $message) {
  http_response_code($code);
  $res = ["error" => $message];
  $jsonRes = json_encode($res);
  exit($jsonRes);
}