<?php
/**
 * phparess
 *
 * @author  Tyler Christensen
 * @license MIT
 * @version 0.1
 */

namespace Tylerchr\Phparess;

class Feed
{

    private $rss_version;
    private $atom;

    /**
     * @var Channel
     */
    private $channel;

    public function __construct($rss_version = "2.0", $atom = true)
    {
        $this->rss_version = $rss_version;
        $this->atom        = $atom === true ? " xmlns:atom=\"http://www.w3.org/2005/Atom\"" : "";
    }

    public function setChannel(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function __toString()
    {

        $item_rows = array(
            "<?xml version=\"1.0\"?>",
            "<rss version=\"" . $this->rss_version . "\"" . $this->atom . ">",
            $this->channel->channel_tabbed(),
            "</rss>"
        );

        return implode("\n", $item_rows);

    }

}