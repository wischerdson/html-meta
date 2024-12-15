<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
	->in(__DIR__)
	->exclude(['vendor']);

$config = new Config();
$config->setFinder($finder);
$config->setRiskyAllowed(true);
$config->setIndent("\t");
$config->setLineEnding("\n");
$config->setRules([
	'@PhpCsFixer:risky' => true,
	'yoda_style' => [
		'always_move_variable' => true,
		'equal' => false,
		'identical' => false,
		'less_and_greater' => false
	],
	'trailing_comma_in_multiline' => [
		'elements' => []
	],
	'fully_qualified_strict_types' => false,
	'global_namespace_import' => false,
	'static_lambda' => false
]);

return $config;
