<?php
namespace CodeOrange\Jot\Docs;

/**
 * Represents a return value, can be a JSON string
 */
class ReturnValue {
	public static $OTHER = 0;
	public static $JSON = 1;

	private $type;
	private $value;

	public function __construct($type, $value) {
		$this->type = $type;

		switch ($type) {
			case self::$JSON:
				$this->value = json_encode(json_decode($value), JSON_PRETTY_PRINT);
				break;
			case self::$OTHER:
			default:
				$this->value = $value;
		}
	}

	public function getType() {
		return $this->type;
	}
	public function getValue() {
		return $this->value;
	}
}