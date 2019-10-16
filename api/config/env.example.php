<?php

$environmentVariables = [
  "HOST" => "",
  "DBNAME" => "",
  "USERNAME" => "",
  "PASSWORD" => ""
];

foreach ($environmentVariables as $name => $var)
  putenv($name . "=" . $var);