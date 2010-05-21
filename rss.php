<?php

require("class.phparess.php");

// test building an object
$items[] = new phparess_item(array(
	"title"=>"This is the title",
	"link"=>"http://www.google.com/=This_(is)_it-2/!@#$%^&*()_+",
	"description"=>"The description of _my_ awesome site"
));

$items[] = new phparess_item(array(
	"title"=>"Secondary item",
	"link"=>"http://www.apple.com/macbookpro",
	"description"=>"Apple has a cool laptop product."
));

$items[] = new phparess_item(array(
	"title"=>"Tertiary item",
	"link"=>"http://codeprinciples.com/#page2",
	"description"=>"Apple has a <b>cool</b> laptop product."
));

// create a channel
$channel = new phparess_channel(array(
	"title"=>"phparess test feed",
	"link"=>"http://github.com/tylerchr/phparess",
	"description"=>"A set of PHP classes for writing basic RSS feeds"
));
$channel->addItems($items);

// create a phparess feed, and display it
$rss = new phparess();
$rss->setChannel($channel);

header('Content-type: application/rss+xml');
echo $rss;

?>