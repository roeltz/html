<?php

namespace Roeltz\HTML;

class HTML {

	static function __callStatic($method, $args) {
		return new HTMLTag($method, ...$args);
	}

	static function tag($type, array $attr = [], array $children = []) {
		return new HTMLTag($type, $attr, $children);
	}
	
	static function defaultAttr($name, $value, array $extra = []) {
		$attr = is_array($value) ? $value : [$name=>$value];
	
		if ($extra)
			$attr = array_merge($extra, $attr);
	
		return $attr;
	}
	
	static function a($href, $text, array $attr = []) {
		$attr = array_merge(["href"=>$href], $attr);
		return new HTMLTag("a", $attr, [$text]);
	}
	
	static function checkbox($attr = null, $value = null, $checked = false) {
		$attr = self::defaultAttr("name", $attr, ["type"=>"checkbox", "value"=>$value, "checked"=>$checked]);
		return new HTMLTag("input", $attr);
	}
	
	static function input($type, $attr, $value = null) {
		$attr = self::defaultAttr("name", $attr, ["type"=>$type, "value"=>$value]);
		return new HTMLTag("input", $attr);
	}
	
	static function img($src, $alt = null) {
		return new HTMLTag("img", ["src"=>$src, "alt"=>$alt]);
	}
	
	static function label($text, $for = null) {
		if ($for instanceof HTMLTag) {
			if ($id = @$for->attr["id"]) {
				$for = $id;
			} else {
				$id = uniqid();
				$for->attr("id", $id);
				$for = $id;
			}
		}
	
		return new HTMLTag("label", ["for"=>$for], [$text]);
	}
	
	static function p($children) {
		if (is_string($children))
			$children = [$children];
		return new HTMLTag("p", [], $children);
	}
	
	static function pre($text) {
		return new HTMLTag("pre", [], [$text]);
	}
	
	static function radio($attr, $value = null, $checked = false) {
		$attr = self::defaultAttr("name", $attr, ["type"=>"radio", "value"=>$value, "checked"=>$checked]);
		return new HTMLTag("input", $attr);
	}
	
	static function select($attr, array $options = [], $selectedValue = "") {
		$attr = self::defaultAttr("name", $attr);
		$select = new HTMLTag("select", $attr);
	
		foreach ($options as $value=>$text) {
			$select->append(new HTMLTag("option", [
				"value"=>$value,
				"selected"=>(string) $value === (string) $selectedValue
			], [$text]));
		}
	
		return $select;
	}
	
	static function textarea($attr, $value) {
		$attr = self::defaultAttr("name", $attr);
		return new HTMLTag("textarea", $attr, [$value]);
	}
}