<?php

/**
 * Handle the feed's content if it is RSS formatted.
 */
function handleRss($doc, &$feedsContent) {
  $itemsXML = $doc->getElementsByTagName("item");
  foreach ($itemsXML as $itemXML) {
    $item = [];

    /* RSS required fields */
    $titleNodes = $itemXML->getElementsByTagName("title");
    if (sizeof($titleNodes) > 0)
      $item["title"] = trim($titleNodes[0]->textContent);

    $linkNodes = $itemXML->getElementsByTagName("link");
    if (sizeof($linkNodes) > 0)
      $item["link"] = $linkNodes[0]->textContent;

    $descriptionNodes = $itemXML->getElementsByTagName("description");
    if (sizeof($descriptionNodes) > 0)
      $item["description"] = trim($descriptionNodes[0]->textContent);
    
    /* RSS optional fields */
    $pubDateNodes = $itemXML->getElementsByTagName("pubDate");
    if (sizeof($pubDateNodes) > 0) {
      $date = $pubDateNodes[0]->textContent;
      $item["date"] = DateTime::createFromFormat(DateTimeInterface::RSS, $date);
    }

    $enclosureNodes = $itemXML->getElementsByTagName("enclosure");
    if (sizeof($enclosureNodes) > 0) {
      $enclosure = $enclosureNodes[0];
      if ($enclosure->hasAttribute("url"))
        $item["img"] = $enclosure->getAttribute("url");
    }

    //$feedsContent[] = $item;
    insertItem($item, $feedsContent);
  }
}

/**
 * Handle the feed's content if it is Atom formatted.
 */
function handleAtom($doc, &$feedsContent) {
  $entriesXML = $doc->getElementsByTagName("entry");
  foreach ($entriesXML as $entryXML) {
    $entry = [];

    /* Atom required fields */
    $titleNodes = $entryXML->getElementsByTagName("title");
    if (sizeof($titleNodes) > 0)
      $entry["title"] = trim($titleNodes[0]->textContent);

    $updatedNodes = $entryXML->getElementsByTagName("updated");
    if (sizeof($updatedNodes) > 0) {
      $date = $updatedNodes[0]->textContent;
      $entry["date"] = DateTime::createFromFormat(DateTimeInterface::ATOM, $date);
    }
    
    /* Atom optional fields */
    $summaryNodes = $entryXML->getElementsByTagName("summary");
    if (sizeof($summaryNodes) > 0)
      $entry["description"] = trim($summaryNodes[0]->textContent);

    $linkNodes = $entryXML->getElementsByTagName("link");
    if (sizeof($linkNodes) > 0) {
      $link = $linkNodes[0];
      if ($link->hasAttribute("href"))
        $entry["link"] = $link->getAttribute("href");
    }

    $logoNodes = $entryXML->getElementsByTagName("logo");
    if (sizeof($logoNodes) > 0)
      $entry["img"] = $logoNodes[0]->textContent;

    //$feedsContent[] = $entry;
    insertItem($entry, $feedsContent);
  }
}

/**
 * Insert the feed item in the correct position in the list.
 */
function insertItem(&$item, &$feedsContent) {
  preprocessItem($item);
  $date = $item["date"];
  // find the position to insert the item
  $i = 0;
  while ($i < sizeof($feedsContent) && $feedsContent[$i]["date"] >= $date)
    $i++;
    
  array_splice($feedsContent, $i, 0, [$item]);
}

/**
 * Fill the missing fields of the item and prevent script injection.
 */
function preprocessItem(&$item) {
  if (!isset($item["title"]))
    $item["title"] = "No title";
  //else $item["title"] = htmlspecialchars($item["title"], ENT_QUOTES, "UTF-8");

  if (!isset($item["description"]))
    $item["description"] = "";
  //else $item["description"] = htmlspecialchars($item["description"], ENT_QUOTES, "UTF-8");

  if (!isset($item["link"]))
    $item["link"] = "#";
  
  if (!isset($item["date"]) || !$item["date"])
    $item["date"] = new DateTime("now");
}