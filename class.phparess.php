<?php

/*
	-- author --
	AUTHOR:		Tyler Christensen
	DATE:		20 May 2010
	CONTACT:	codeprinciples.com
				github.com/tylerchr
				@slasht
				
	-- license --				
	LICENSE:	MIT
				http://www.opensource.org/licenses/mit-license.php
				
				Essentially, you're free to use this code for any purpose, commercial or
				not, as long as you retain this notice in the source code.
	
	-- version --			
	UPDATED:	21 May 2010
	VERSION:	0.1
*/

class phparess {

	private $rss_version;
	private $channel;
	
	public function __construct($rss_version="2.0") {
		$this->rss_version = $rss_version;
	}
	
	public function setChannel($channel) {
		if (is_object($channel) && get_class($channel) == "phparess_channel") {
			$this->channel = $channel;
		}
	}
	
	public function __toString() {
		
		$item_rows = array(
			"<?xml version=\"1.0\"?>",
			"<rss version=\"" . $this->rss_version . "\">",
			$this->channel->channel_tabbed(),
			"</rss>"
		);
		
		return implode("\n", $item_rows);

	}
	
}

class phparess_channel {
	
	private $properties;
	private $items;
	
	public function __construct($properties=array()) {
		
		$this->properties = null;
		
		if (	array_key_exists("title", $properties) &&
				array_key_exists("link", $properties) &&
				array_key_exists("description", $properties)) {
				
				foreach ($properties as $key => $value) {
					if ($this->_is_legal_key($key))
						$this->properties[$key] = $value;
				}		
		}
		
	}
	
	public function addItems($item_array) {
		if (count($item_array) > 0) {
			foreach ($item_array as $value) {
				$this->addItem($value);	
			}	
		}	
	}
	
	public function addItem($item) {
		if (is_object($item) && get_class($item) == "phparess_item") {
			$this->items[] = $item;
		} else {
			echo "[outside criteria]\n";	
		}
	}
	
	// reading the data
	
	public function __toString() {

		if (!is_null($this->properties)) {
			foreach ($this->properties as $key => $value) {
				$value = new _phparess_xml_tag($key, $value);
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
	
	public function channel() {
		return $this->allItems();
	}
	
	public function channel_tabbed() {
		return $this->_tab_rows($this->__toString());	
	}
	
	public function allItems($pre_tab=false) {
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
	
	private function _tab_rows($string) {
		$rows = explode("\n", $string);
		foreach ($rows as $value) {
			$new_rows[] = "\t" . $value . "\n";	
		}
		return implode("", $new_rows);
	}
	
	private function _is_legal_key($key) {
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
			"skipDays"
		);
		
		return in_array($key, $legal_keys);
	}
		
}

class phparess_item {
	
	private $details;
	
	public function __construct($details) {
		
		$this->details = null;
		
		if (	array_key_exists("title", $details) &&
				array_key_exists("link", $details) &&
				array_key_exists("description", $details)) {
				
				foreach ($details as $key => $value) {
					if ($this->_is_legal_key($key))
						$this->details[$key] = $value;
				}
		
		}
		
	}
	
	public function __toString() {

		if (!is_null($this->details)) {
			foreach ($this->details as $key => $value) {
				$value = new _phparess_xml_tag($key, $value);
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
	
	private function _is_legal_key($key) {
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

class phparess_xml {

	public function CompileXML($array) {
		foreach ($array as $key => $value) {
			
			echo "<" . $key . ">";
			
			if (is_string($value)) {
				echo $value;
			} else if (is_array($value)) {
				$value = $this->CompileXML($value);	
			}
			
			echo "</" . $key . ">";
		}
	}
	
	//
	// Internal methods
	//
	
	private function _compile_tag($tag, $contents) {
		return "<" . $tag . ">" . $contents . "</" . $tag . ">";
	}
	
}

class _phparess_xml_tag {
	
	public $tag;
	public $attributes;
	public $contents;
	
	public function __construct($tag="tag", $contents="", $attributes=array()) {
		
		$this->tag = $tag;
		$this->contents = $contents;
		$this->attributes = $attributes;
		
	}
	
	public function __toString() {
	
		if (count($this->attributes) > 0) {
			foreach ($this->attributes as $key => $value) {
				$attribute[] = $key . "=\"" . $this->_clear_value($value) . "\"";
			}
			$attribute_string = " " . implode(" ", $attribute);
		}
		
		if (strlen($this->contents) == strlen(strip_tags($this->contents)) && strlen($this->contents) == strlen(htmlspecialchars($this->contents))) {
			$string = $this->_clear_value($this->contents);
		} else {
			$string = $this->_clear_string($this->contents);	
		}
		
		return "<" . $this->_clear_tag($this->tag) . $attribute_string . ">" . $string . "</" . $this->_clear_tag($this->tag) . ">";
	}
	
	// cleans invalid characters out
	private function _clear_tag($tag) {
		return preg_replace("/[^a-zA-Z0-9_]/", "", $tag);
	}
	
	private function _clear_value($value) {
		return $value;
	}
	
	private function _clear_string($string) {
		return "<![CDATA[" . $string . "]]>";
	}
	
}

?>
