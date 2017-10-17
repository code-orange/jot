<?php
namespace CodeOrange\Jot\Commands;

use CodeOrange\Jot\Docs\DocsGenerator;
use CodeOrange\Jot\Docs\MarkdownGenerator;
use Illuminate\Console\Command;

class ApiDocsGenerator extends Command {
	protected $signature = 'jot:generate';
	protected $description = 'Generates API documentation, outputs to stdout.';

	private $generator;
	private $markdown;

	public function __construct(DocsGenerator $generator, MarkdownGenerator $markdownGenerator) {
		parent::__construct();

		$this->generator = $generator;
		$this->markdown = $markdownGenerator;
	}

	public function fire() {
		$resources = $this->generator->getResources();
		$docs = $this->markdown->getMarkdownDocumentation($resources);
		$this->info($docs);
	}

	public function handle() {
		self::fire();
	}
}
