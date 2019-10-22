<?php

require_once("config/core.php");

header("Access-Control-Allow-Methods: POST");

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

$token = $_SERVER["HTTP_AUTHORIZATION"];
$user = authenticate($token, $db);

$url = $_POST["url"];
if (!isset($url))
  exitError(400, "Missing 'url' field.");

if (!filter_var($url, FILTER_VALIDATE_URL))
  exitError(400, "The provided string does not follow the URL format.");

$xml = @simplexml_load_file($url);
if (!$xml)
  exitError(400, "The given feed does not respond.");

$xmlErrors = libxml_get_errors();
if ($xmlErrors)
  exitError(400, "The given feed does not follow the XML format.");

// Code
$sql = "SELECT * FROM `Feed` WHERE url = :url;";