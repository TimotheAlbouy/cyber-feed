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
    if ($doc->firstChild->nodeName === "rss")
      handleRss($doc, $feedsContent);
    else if ($doc->firstChild->nodeName === "feed")
      handleAtom($doc, $feedsContent);
  }
}

function handleRss($doc, &$feedsContent) {
  $itemsXML = $doc->getElementsByTagName("item");
  foreach ($itemsXML as $itemXML) {
    $item = [];
    /* RSS required fields */
    // title
    $titleNodes = $itemXML->getElementsByTagName("title");
    if (sizeof($titleNodes) > 0)
      $item["title"] = $titleNodes[0]->textContent;
    // link
    $linkNodes = $itemXML->getElementsByTagName("link");
    if (sizeof($linkNodes) > 0)
      $item["link"] = $linkNodes[0]->textContent;
    // description
    $descriptionNodes = $itemXML->getElementsByTagName("description");
    if (sizeof($descriptionNodes) > 0)
      $item["description"] = $descriptionNodes[0]->textContent;
    /* RSS optional fields */
    // pubDate
    $pubDateNodes = $itemXML->getElementsByTagName("pubDate");
    if (sizeof($pubDateNodes) > 0)
      $item["date"] = $pubDateNodes[0]->textContent;
    // enclosure
    $enclosureNodes = $itemXML->getElementsByTagName("enclosure");
    if (sizeof($enclosureNodes) > 0) {
      $enclosure = $enclosureNodes[0];
      if ($enclosure->hasAttribute("url"))
        $item["img"] = $enclosure->getAttribute("url");
    }

    $feedsContent[] = $item;
  }
}

function handleAtom($doc, &$feedsContent) {
  $entriesXML = $doc->getElementsByTagName("entry");

  foreach ($entriesXML as $entryXML) {
    $entry = [];
    /* Atom required fields */
    // title
    $titleNodes = $entryXML->getElementsByTagName("title");
    if (sizeof($titleNodes) > 0)
      $entry["title"] = $titleNodes[0]->textContent;
    // updated
    $updatedNodes = $entryXML->getElementsByTagName("updated");
    if (sizeof($updatedNodes) > 0)
      $entry["date"] = $updatedNodes[0]->textContent;
    /* Atom optional fields */
    // description
    $summaryNodes = $entryXML->getElementsByTagName("summary");
    if (sizeof($summaryNodes) > 0)
      $entry["description"] = $summaryNodes[0]->textContent;
    // link
    $linkNodes = $entryXML->getElementsByTagName("link");
    if (sizeof($linkNodes) > 0) {
      $link = $linkNodes[0];
      if ($link->hasAttribute("href"))
        $entry["link"] = $link->getAttribute("href");
    }
    // logo
    $logoNodes = $entryXML->getElementsByTagName("logo");
    if (sizeof($logoNodes) > 0)
      $entry["img"] = $logoNodes[0]->textContent;

    $feedsContent[] = $entry;
  }
}

$res = ["feeds" => $feedsContent];
http_response_code(200);
echo(json_encode($res));