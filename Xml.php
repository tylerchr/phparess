<?php
/**
 * phparess
 *
 * @author  Tyler Christensen
 * @license MIT
 * @version 0.2
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
}