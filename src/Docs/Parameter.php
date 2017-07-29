<?php
namespace CodeOrange\Jot\Docs;

use phpDocumentor\Reflection\DocBlock\Tag;

/**
 * Represents a documented parameter, either in the route or in the request
 */
class Parameter {
	public $name;
	public $description;
	public $type;
	public $in;

	public function __construct($name, $description, $type, $in) {
		$this->name = $name;
		$this->description = $description;
		$this->type = $type;
		$this->in = $in;
	}

	public static function fromTag(Tag\ParamTag $param) {
		return new static(str_replace('$', '', $param->getVariableName()), $param->getDescription(), $param->getType(), 'request');
	}

	public static function fromRouteParameter($name) {
		return new static($name, '', '', 'path');
	}
}
