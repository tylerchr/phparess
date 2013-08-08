<?php

namespace Tylerchr\Phparess\Tests;

use Tylerchr\Phparess\Channel;
use Tylerchr\Phparess\Feed;
use Tylerchr\Phparess\Item;

class FeedTest extends \PHPUnit_FrameWork_TestCase
{
    protected $expected;

    public function setUp()
    {
        $this->expected = file_get_contents('Tests/expected.rss');
    }

    public function testFeed()
    {
        // test building an object
        $items[] = new Item(array(
            "title"       => "This is the title",
            "link"        => "http://www.google.com/=This_(is)_it-2/!@#$%^&*()_+",
            "description" => "The description of _my_ awesome site"
        ));

        $items[] = new Item(array(
            "title"       => "Secondary item",
            "link"        => "http://www.apple.com/macbookpro",
            "description" => "Apple has a cool laptop product."
        ));

        $items[] = new Item(array(
            "title"       => "Tertiary item",
            "link"        => "http://codeprinciples.com/#page2",
            "description" => "Apple has a <b>cool</b> laptop product."
        ));

        // create a channel
        $channel = new Channel(array(
            "title"       => "phparess test feed",
            "link"        => "http://github.com/tylerchr/phparess",
            "description" => "A set of PHP classes for writing basic RSS feeds"
        ));
        $channel->addItems($items);

        // create a phparess feed, and display it
        $rss = new Feed();
        $rss->setChannel($channel);

        $this->assertEquals($this->expected, $rss->__toString());
    }
}