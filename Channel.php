<?php
/**
 * phparess
 *
 * @author  Tyler Christensen
 * @license MIT
 * @version 0.2
 */

namespace Tylerchr\Phparess;

class Channel
{

    private $properties;
    private $items;

    public function __construct($properties = array())
    {

        $this->properties = null;

        if (array_key_exists("title", $properties) &&
            array_key_exists("link", $properties) &&
            array_key_exists("description", $properties)
        ) {

            foreach ($properties as $key => $value) {
                if ($this->_is_legal_key($key)) {
                    $this->properties[$key] = $value;
                }
            }
        }

    }

    public function addItems($item_array)
    {
        if (count($item_array) > 0) {
            foreach ($item_array as $value) {
                $this->addItem($value);
            }
        }
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    // reading the data

    public function __toString()
    {

        if (!is_null($this->properties)) {
            foreach ($this->properties as $key => $value) {

                switch ($key) {

                    case "atom:link":
                        $value = new Tag($key, "", array(
                                'rel'  => "self",
                                'type' => "application/rss+xml",
                                'href' => $value
                            )
                        );
                        break;

                    default:
                        $value = new Tag($key, $value);

                }

                $item_rows[] = "\t" . $value;
            }

            array_unshift($item_rows, "<channel>");

            array_push($item_rows, $this->allItems(true));
            array_push($item_rows, "</channel>\n");

            return implode("\n", $item_rows);
        } else {
            return null;
        }

    }

    public function channel()
    {
        return $this->allItems();
    }

    public function channel_tabbed()
    {
        return $this->_tab_rows($this->__toString());
    }

    public function allItems($pre_tab = false)
    {
        if (count($this->items) > 0) {
            $items = implode("", $this->items);
            if ($pre_tab) {
                return $this->_tab_rows($items);
            } else {
                return $items;
            }
        } else {
            return "";
        }
    }

    // internal methods

    private function _tab_rows($string)
    {
        $rows = explode("\n", $string);

        $new_rows = array();

        foreach ($rows as $value) {
            $new_rows[] = "\t" . $value . "\n";
        }
        return implode("", $new_rows);
    }

    private function _is_legal_key($key)
    {
        $legal_keys = array(
            "title",
            "link",
            "description",
            "language",
            "copyright",
            "managingEditor",
            "webMaster",
            "pubDate",
            "lastBuildDate",
            "category",
            "generator",
            "docs",
            "cloud",
            "ttl",
            "image",
            "rating",
            "textInput",
            "skipHours",
            "skipDays",
            "atom:link"
        );

        return in_array($key, $legal_keys);
    }

}