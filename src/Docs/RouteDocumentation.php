<?php
namespace CodeOrange\Jot\Docs;


use Illuminate\Routing\Route;
use phpDocumentor\Reflection\DocBlock;
use ReflectionClass;
use ReflectionException;

/**
 * Represents the documentation for a route in an application
 */
class RouteDocumentation {
	protected $method;
	protected $uri;
	protected $name;
	protected $description;
	protected $params;
	protected $return;
	protected $deprecated;

	public $resource;

	public function __construct(Route $route) {
		$this->method = $route->methods()[0];
		$this->setUri($route->uri());

		// Load DocBlock
		$array = explode("@", $route->getActionName());
		$class = $array[0];
		if ($class === "Closure") {
			// Quick work-around to skip Closure based routes
			$this->setUri('/');
			return;
		}
		$methodName = (count($array) > 1) ? $array[1] : '';
		$reflector = new ReflectionClass($class);
		try {
			$docBlock = new DocBlock($reflector->getMethod($methodName));
		} catch (ReflectionException $e) {
			echo "Warning: method $methodName not found in $class\n";
			// Quick work-around to skip Closure based routes
			$this->setUri('/');
			return;
		}

		// Set properties
		$this->name = $docBlock->getShortDescription();
		$this->description = $docBlock->getLongDescription()->getContents();

		$this->processParameters($route, $docBlock);

		$returntags = $docBlock->getTagsByName('return');
		if (count($returntags) > 0) {
			$tag = $returntags[0];
			$type = ReturnValue::$OTHER;

			switch($tag->getType()) {
				case '\JsonResponse':
					$type = ReturnValue::$JSON;
					break;
			}

			$this->return = new ReturnValue($type, $tag->getDescription());
		} else {
			$this->return = new ReturnValue(ReturnValue::$OTHER, '');
		}

		$deprecation = $docBlock->getTagsByName('deprecated');
		if (count($deprecation) > 0) {
			$tag = $deprecation[0];
			$notice = $tag->getContent();
			$this->deprecated = empty($notice) ? true : $notice;
		}
	}

	/**
	 * Tries to apply a prefix to this route.
	 * If the route matches the prefix, the URI is updated with the prefix removed and true is returned.
	 * If the route does not match, this returns false.
	 *
	 * @param $prefix
	 * @return bool
	 */
	public function applyFilter($prefix) {
		if (empty($prefix) || $prefix[0] !== '/') {
			$prefix = '/' . $prefix;
		}

		if (strpos($this->uri, $prefix) === 0) {
			$this->setUri(substr($this->uri, strlen($prefix)));
			return true;
		} else {
			return false;
		}
	}

	public function toString() {
		$params = print_r($this->params, true);
		return $this->method . ' ' . $this->uri . " ({$this->name})\n\n$params\n\n{$this->return->getValue()}\n\n";
	}

	private function setResource() {
		$this->resource = explode('/', $this->uri)[1];
	}

	private function setUri($uri) {
		if (empty($uri) || $uri[0] !== '/') {
			$uri = '/' . $uri;
		}
		$this->uri = $uri;
		$this->setResource();
	}

	private function processParameters(Route $r, DocBlock $block) {
		$params = [];

		foreach ($block->getTagsByName('param') as $tag) {
			$p = Parameter::fromTag($tag);
			$params[$p->name] = $p;
		}

		foreach ($r->parameterNames() as $name) {
			if (array_key_exists($name, $params)) {
				$params[$name]->in = 'path';
			} else {
				$params[$name] = Parameter::fromRouteParameter($name);
			}
		}

		$this->params = $params;
	}

	public function getKey() {
		return $this->method . '-' . $this->uri;
	}

	public function getMethod() {
		return $this->method;
	}

	public function getUri() {
		return $this->uri;
	}

	public function getName() {
		return $this->name;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getParams() {
		return $this->params;
	}

	public function getReturn() {
		return $this->return;
	}

	public function getResource() {
		return $this->resource;
	}

	public function isDeprecated() {
		return isset($this->deprecated);
	}

	public function getDeprecationMessage() {
		return ($this->deprecated === true) ? false : $this->deprecated;
	}
}

