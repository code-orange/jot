<?php
namespace CodeOrange\Jot\Commands;

use CodeOrange\Jot\Docs\DocsGenerator;
use CodeOrange\Jot\Docs\RouteDocumentation;
use Illuminate\Console\Command;

class CoverageCheckCommand extends Command {
	protected $signature = 'jot:coverage {--return : Whether return values need to be documented}';
	protected $description = 'Checks documentation coverage, quits with error code if missing documentation is found.';

	private $generator;

	public function __construct(DocsGenerator $generator) {
		parent::__construct();

		$this->generator = $generator;
	}

	public function fire() {
		$checkReturn = $this->option('return');

		$messages = $this->generator->getRouteDocumentation()->map(function (RouteDocumentation $r) use ($checkReturn) {
			if ($r->getName() == '') {
				return $r->getMethod() . ' ' . $r->getUri() . ' is not documented';
			}
			if ($checkReturn && $r->getReturn()->getValue() == '') {
				return $r->getMethod() . ' ' . $r->getUri() . ' has no return type documented';
			}
			return null;
		})->filter();

		if ($messages->count() == 0) {
			$this->info('Everything is covered!');
		} else {
			foreach ($messages as $m) {
				$this->error($m);
			}
			exit(1);
		}
	}

	public function handle() {
		self::fire();
	}
}