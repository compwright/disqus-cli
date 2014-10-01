<?php

namespace compwright\DisqusCLI;

use Aura\Cli\Stdio;
use DisqusAPI;

class Client
{
	public $helpText = '';

	protected $api;
	protected $stdio;

	public function __construct(DisqusAPI $api, Stdio $stdio)
	{
		$this->stdio = $stdio;
		$this->api = $api;
	}

	public function dispatch(array $args)
	{
		$scriptName = array_shift($args);

		// Special case: no arguments, or help argument passed
		if (empty($args) || isset($args['help']) || $args[1] === 'help')
		{
			$this->help();
			return;
		}

		// Normal case: script-name <resource> <verb> [options]
		$resource = array_shift($args);
		$verb = array_shift($args);
		$options = [
			'api_key' => getenv('DISQUS_API_KEY'),
			'api_secret' => getenv('DISQUS_API_SECRET'),
			'access_token' => getenv('DISQUS_ACCESS_TOKEN'),
		];

		foreach ($args as $k => $v)
		{
			$options[ltrim($k, '-')] = $v;
		}

		$call = [$this->api->$resource, $verb];
		$response = call_user_func_array($call, [$options]);

		$string = print_r($response, TRUE);
		$this->stdio->outln($string);
	}

	public function help()
	{
		$this->stdio->outln($this->helpText);
	}
}
