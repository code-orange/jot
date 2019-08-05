<?php
namespace CodeOrange\Jot\Docs;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;

class DocsGenerator {
	private $router;

	public function __construct(Router $router) {
		$this->router = $router;
	}

	/**
	 * Gets all documented routes in the application, grouped by resource
	 *
	 * @return Collection<string, Collection<RouteDocumentation>>
	 */
	public function getResources() {
		return $this->getRouteDocumentation()->groupBy('resource');
	}

	/**
	 * Gets all documented routes in the application
	 *
	 * @return Collection<RouteDocumentation>
	 */
	public function getRouteDocumentation() {
		return collect($this->router->getRoutes())->map(function (Route $route) {
			return new RouteDocumentation($route);
		})->unique(function (RouteDocumentation $r) {
			return $r->getUri();
		})->filter(function (RouteDocumentation $r) {
			return $r->applyFilter(config('jot.prefix'));
		});
	}
}
