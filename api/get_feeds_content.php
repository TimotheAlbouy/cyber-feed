<?php

require_once("config/core.php");

require_once("model/User.php");
require_once("model/FeedUser.php");

header("Access-Control-Allow-Methods: GET");

$headers = apache_request_headers();

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "GET")
  exitError(405, "Only GET requests are allowed.");

if (!isset($headers["Authorization"]))
  exitError(401, "No token provided.");

$user = User::authenticate($headers["Authorization"]);

// Code
$feeds = FeedUser::findByUsername($user->username);

$res = [];
foreach ($feeds as $feed) {
  $doc = new DOMDocument();
  //$xml = @simplexml_load_file($feed->url);
  $doc->load($feed["url"]);
  if ($doc) {
    $itemsXML = $doc->getElementsByTagName("item");
    foreach ($itemsXML as $itemXML) {
      $item = [
        "title" => $itemXML->getElementsByTagName("title")[0]->textContent,
        "description" => $itemXML->getElementsByTagName("description")[0]->textContent,
        "pubDate" => $itemXML->getElementsByTagName("pubDate")[0]->textContent,
        "link" => $itemXML->getElementsByTagName("link")[0]->textContent
      ];
      $res[] = $item;
    }
    //exitError(400, "Invalid feed (URL invalid or not following the XML format).");
  }
}

http_response_code(200);
echo(json_encode($res));