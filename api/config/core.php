<?php

require_once("Database.php");
require_once("util.php");

// Set the headers of the HTTP responses of the API
header("Content-Type: application/json; charset=UTF-8");  // Response type is JSON
header("Access-Control-Allow-Origin: *");                 // CORS management
header("Access-Control-Max-Age: 3600");                   // Pre-flight response is cached 1 hour
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");