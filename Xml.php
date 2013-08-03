<?php
/**
 * phparess
 *
 * @author  Tyler Christensen
 * @license MIT
 * @version 0.1
 */

namespace Tylerchr\Phparess;

class Xml
{
    public function CompileXML($array)
    {
        foreach ($array as $key => $value) {

            echo "<" . $key . ">";

            if (is_string($value)) {
                echo $value;
            } else {
                if (is_array($value)) {
                    $this->CompileXML($value);
                }
            }

            echo "</" . $key . ">";
        }
    }

    //
    // Internal methods
    //

    private function _compile_tag($tag, $contents)
    {
        return "<" . $tag . ">" . $contents . "</" . $tag . ">";
    }

}