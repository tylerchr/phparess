<?php
/**
 * phparess
 *
 * @author  Tyler Christensen
 * @license MIT
 * @version 0.1
 */

namespace Tylerchr\Phparess;

class Item
{
    private $details;

    public function __construct($details)
    {

        $this->details = null;

        if (array_key_exists("title", $details) &&
            array_key_exists("link", $details) &&
            array_key_exists("description", $details)
        ) {

            foreach ($details as $key => $value) {
                if ($this->_is_legal_key($key)) {
                    $this->details[$key] = $value;
                }
            }

        }

    }

    public function __toString()
    {

        if (!is_null($this->details)) {
            foreach ($this->details as $key => $value) {

                switch ($key) {
                    case "source":
                        $value = new Tag($key, $value['content'], array('url' => $value['url']));
                        break;

                    default:
                        $value = new Tag($key, $value);
                }


                $item_rows[] = "\t" . $value;
            }
            array_unshift($item_rows, "<item>");
            array_push($item_rows, "</item>\n");

            return implode("\n", $item_rows);
        } else {
            return null;
        }

    }

    // internal methods

    private function _is_legal_key($key)
    {
        $legal_keys = array(
            "title",
            "link",
            "description",
            "author",
            "category",
            "comments",
            "enclosure",
            "guid",
            "pubDate",
            "source"
        );

        return in_array($key, $legal_keys);
    }

}