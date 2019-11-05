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

$feedsContent = [];
foreach ($feeds as $feed) {
  $doc = new DOMDocument();
  $doc->load($feed["url"]);
  if ($doc) {
    $itemsXML = $doc->getElementsByTagName("item");
    foreach ($itemsXML as $itemXML) {
      $item = [];
      
      $titleNodes = $itemXML->getElementsByTagName("title");
      if (sizeof($titleNodes) > 0)
        $item["title"] = $titleNodes[0]->textContent;
      
      $descriptionNodes = $itemXML->getElementsByTagName("description");
      if (sizeof($descriptionNodes) > 0)
        $item["description"] = $descriptionNodes[0]->textContent;

      $pubDateNodes = $itemXML->getElementsByTagName("pubDate");
      if (sizeof($pubDateNodes) > 0)
        $item["pubDate"] = $pubDateNodes[0]->textContent;

      $linkNodes = $itemXML->getElementsByTagName("link");
      if (sizeof($linkNodes) > 0)
        $item["link"] = $linkNodes[0]->textContent;

      $enclosureNodes = $itemXML->getElementsByTagName("enclosure");
      if (sizeof($enclosureNodes) > 0) {
        $enclosure = $enclosureNodes[0];
        if ($enclosure->hasAttribute("url"))
          $item["imageUrl"] = $enclosure->getAttribute("url");
        if ($enclosure->hasAttribute("type"))
          $item["imageType"] = $enclosure->getAttribute("type");
      }
      
      $feedsContent[] = $item;
    }
  }
}

$res = ["feeds" => $feedsContent];
http_response_code(200);
echo(json_encode($res));