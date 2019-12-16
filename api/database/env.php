<?php

$environmentVariables = [
  "HOST" => "localhost",
  "DBNAME" => "cyberfeed",
  "USERNAME" => "root",
  "PASSWORD" => ""
];

foreach ($environmentVariables as $name => $var)
  putenv($name . "=" . $var);