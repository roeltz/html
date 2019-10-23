<?php

class HTMLTag {

	const SELF_CLOSING_TYPES = " area base br col command embed hr img input keygen link meta param source track wbr ";

	public $type;

	public $attr;

	public $children;

	function __construct($type, array $attr = [], array $children = []) {
		$this->type = strtolower($type);
		$this->attr = $attr;
		$this->children = $children;
	}

	function append($_) {
		if (is_array($_)) {
			foreach ($_ as $child)
				$this->append($child);
		} elseif (func_num_args() > 1) {
			$this->append(func_get_args());
		} else {
			$this->children[] = $_;
		}

		return $this;
	}

	function attr($name, $value) {
		$this->attr[$name] = $value;

		return $this;
	}

	function child($type, array $attr = [], array $children = []) {
		return $this->append(new HTMLTag($type, $attr, $children));
	}

	function isSelfClosing() {
		return strpos(self::SELF_CLOSING_TYPES, " {$this->type} ") !== false;
	}

	function prepend($child) {
		array_unshift($this->children, $child);

		return $this;
	}

	function render() {
		$html = $this->renderOpeningTag();
		$html .= $this->renderChildren();
		$html .= $this->renderClosingTag();

		return $html;
	}

	function renderChildren() {
		if (!$this->isSelfClosing()) {
			$html = "";

			if ($this->children) {
				foreach ($this->children as $child) {
					if (is_string($child))
						$child = htmlspecialchars($child);

					$html .= $child;
				}
			}

			return $html;
		}
	}

	function renderClosingTag() {
		if (!$this->isSelfClosing())
			return "</{$this->type}>";
	}

	function renderOpeningTag() {
		$html = "<{$this->type}";

		foreach ($this->attr as $name=>$value) {
			if ($value === true) {
				$html .= " $name";
			} elseif ($value !== null && $value !== false) {
				$value = htmlspecialchars($value);
				$html .= " {$name}=\"$value\"";
			}
		}

		$html .= ">";

		return $html;
	}

	function text($text) {
		$this->children = [$text];
	}

	function __toString() {
		return $this->render();
	}
}