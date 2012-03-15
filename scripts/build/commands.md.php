#!/usr/bin/env php
<?php

ob_start();

defined('APPLICATION_PATH') || define(
    'APPLICATION_PATH',
    $_SERVER['argv'][2]
);

set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(APPLICATION_PATH . '/library'),
            realpath(APPLICATION_PATH . '/../../src'),
            APPLICATION_PATH,
            get_include_path(),
        )
    )
);

require_once 'Application.php';
require_once 'Application/Autoloader.php';
require_once 'IRCBot/src/shortFunctions.php';

$application = new \BotLife\Application();
$application->bootstrap();

$module      = new \Botlife\Module\Bar;
$commands    = $module->commands;

$output      = null;
$output     .= '# Commands' . PHP_EOL . PHP_EOL;
foreach ($commands as $command) {
    $command = new $command;
    if (!$command->code) {
        $command->code = 'None';
    }
    $output .= '## ' . $command->code . PHP_EOL . PHP_EOL;
    $output .= '* Code: ' . $command->code . PHP_EOL;
    $regex   = $command->regex;
    $regex   = (is_array($regex)) ? $regex : array($regex);
    foreach ($regex as $expression) {
        $output .= '* Regex: ```' . $expression . '```' . PHP_EOL;
    }
    $output .= PHP_EOL;
}

ob_clean();

echo 'Writing output to ' . $_SERVER['argv'][1] . '.' . PHP_EOL;
file_put_contents($_SERVER['argv'][1], $output);

