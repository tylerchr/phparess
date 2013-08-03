<?php
/**
 * phparess
 *
 * @author  Tyler Christensen
 * @license MIT
 * @version 0.2
 */

namespace Tylerchr\Phparess;

class Tag
{

    public $tag;
    public $attributes;
    public $contents;

    public function __construct($tag = "tag", $contents = "", $attributes = array())
    {

        $this->tag        = $tag;
        $this->contents   = $contents;
        $this->attributes = $attributes;

    }

    public function __toString()
    {

        $attribute_string = "";

        if (count($this->attributes) > 0) {

            $attribute = array();

            foreach ($this->attributes as $key => $value) {
                $attribute[] = $key . "=\"" . $this->_clear_value($value) . "\"";
            }
            $attribute_string = " " . implode(" ", $attribute);
        }

        if (strlen($this->contents) == strlen(strip_tags($this->contents)) && strlen($this->contents) == strlen(
                htmlspecialchars($this->contents)
            )
        ) {
            $string = $this->_clear_value($this->contents);
        } else {
            $string = $this->_clear_string($this->contents);
        }

        return "<" . $this->_clear_tag($this->tag) . $attribute_string . ">" . $string . "</" . $this->_clear_tag(
            $this->tag
        ) . ">";
    }

    // cleans invalid characters out
    private function _clear_tag($tag)
    {
        return preg_replace("/[^a-zA-Z0-9_:]/", "", $tag);
    }

    private function _clear_value($value)
    {
        return $value;
    }

    private function _clear_string($string)
    {
        return "<![CDATA[" . $string . "]]>";
    }

}
