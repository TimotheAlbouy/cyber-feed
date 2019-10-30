<?php

require_once("config/core.php");

header("Access-Control-Allow-Methods: GET");

// Guard clauses
if ($_SERVER["REQUEST_METHOD"] !== "GET")
  exitError(405, "Only GET requests are allowed.");

$token = $_SERVER["HTTP_AUTHORIZATION"];
$user = authenticate($token, $db);
$username = user["username"];
$url = $_GET["url"];
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
$result = query($sql);

$content = file_get_contents($sql); // get XML string
$x = new SimpleXmlElement($content); // load XML string into object

echo '<ul class="blog-posts clearfix">';
$i=1; // start count at 1

// loop through posts
foreach($x->channel->item as $entry) {
        
    // format blog post output
    echo '<li class="blog-post">';
    echo '<a href="'.$entry->link.'" title="'.$entry->title.'" target="_blank">' . $entry->title . '</a>'; // output link & title
    echo $entry->description; // return post content
    echo '</li>';

    $i++; // increment counter

    if($i >= 3) // if counter more than 2 - quit
		break;        
	}
    echo "</ul>";


?>