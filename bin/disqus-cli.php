#!/usr/bin/env php
<?php

namespace compwright\DisqusCLI;

$self = basename(__FILE__);
$options = [
	'help' => 'Display this help message'
];

// If this package is included in another project,
// Composer will move this script into vendor/bin;
// so this will work as a standalone app or use
// the root project's autoloader
if (file_exists('vendor/autoload.php'))
{
	$basedir = getcwd();
	require_once 'vendor/autoload.php';
}
else
{
	fwrite(STDERR, 'Could not find the vendor/autoload.php file' . PHP_EOL);
	exit(1);
}

use Aura\Cli\CliFactory;
use Aura\Cli\Status;
use Aura\Cli\Help;
use Aura\Cli\Context\GetoptParser;
use Aura\Cli\Context\OptionFactory;
use Dotenv;
use DisqusAPI;
use DisqusInterfaceNotDefined;

// Aura CLI library dependencies
$auraCli = new CliFactory;
$context = $auraCli->newContext($_SERVER);
$stdio = $auraCli->newStdio();

// Assemble the script usage instructions
$help = new Help(new OptionFactory);
$help->setSummary('A lightweight command line client for the Disqus API');
$help->setUsage("<resource> <verb> [optionlist]");
$help->setDescr('For a full list of Disqus API resources, verbs, and options, visit https://disqus.com/api/docs');
$help->setOptions($options);

// Parse the command line
$optionsParser = new GetoptParser(new OptionFactory);
$optionsParser->setOptions($options);
$optionsParser->parseInput($context->argv->get());

try
{
	// Load the configuration .env file
	Dotenv::load(getcwd());
	Dotenv::required([
		'DISQUS_API_KEY',
		'DISQUS_API_SECRET',
		'DISQUS_ACCESS_TOKEN',
	]);
}
catch (\RuntimeException $e)
{
	$stdio->errln($e->getMessage());
	exit(Status::CONFIG);
}
catch (\InvalidArgumentException $e)
{
	$stdio->errln($e->getMessage());
	exit(Status::CONFIG);
}

try
{
	// Initialize the client
	$api = new DisqusAPI(getenv('DISQUS_API_SECRET'));
	$client = new Client($api, $stdio);
	$client->helpText = $help->getHelp($self);

	// Call the API, display the result, and exit
	$result = $client->dispatch($optionsParser->getValues());

	exit($result);
}
catch (DisqusInterfaceNotDefined $e)
{
	// Invalid resource or verb
	error_reporting($level);
	$stdio->errln('<<red>>');
	$stdio->errln($e->getMessage());
	$stdio->errln('<<reset>>');
	exit(Status::USAGE);
}
catch (\Exception $e)
{
	// Catch-all
	error_reporting($level);
	$stdio->errln('<<red>>');
	$stdio->errln($e->getMessage());
	$stdio->errln('<<reset>>');
	exit(Status::FAILURE);
}
