<?php
namespace CodeOrange\Jot;

use CodeOrange\Jot\Commands\ApiDocsGenerator;
use CodeOrange\Jot\Commands\CoverageCheckCommand;
use Illuminate\Support\ServiceProvider;

class JotServiceProvider extends ServiceProvider {
	public function register() {
		$this->commands([
			ApiDocsGenerator::class,
			CoverageCheckCommand::class
		]);
	}

	public function boot() {
		$this->publishes([
			__DIR__ . '/config/jot.php' => config_path('jot.php')
		]);
		$this->mergeConfigFrom(__DIR__ . '/config/jot.php', 'jot');
		$this->loadViewsFrom(__DIR__ . '/views', 'jot');
	}
}
