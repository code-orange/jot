<?php
namespace CodeOrange\Jot\Docs;

use Illuminate\Support\Collection;
use Illuminate\View\Factory;

class MarkdownGenerator {
	/**
	 * Generates markdown documentation for a given collection of resources
	 *
	 * @param Collection <string, Collection<RouteDocumentation>> $resources
	 * @return \Illuminate\View\View
	 */
	public function getMarkdownDocumentation(Collection $resources) {
		return view('jot::documentation', ['resources' => $resources])->render();
	}
}
