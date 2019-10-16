<?php

require_once("api-common.php");

// Guard clauses

if ($_SERVER["REQUEST_METHOD"] !== "POST")
  exitError(405, "Only POST requests are allowed.");

$username = $POST["username"];
$password = $POST["password"];

if (!isset($username) || !isset($password))
  exitError(400, "Missing 'username' or 'password' fields.");

if (ctype_alnum($username))
  exitError(400, "Only alphanumerical characters are allowed for the username.");

if(false)
	exitError(401, "Not valid username or password.")

