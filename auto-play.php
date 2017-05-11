<?php

require './vendor/autoload.php';

$level = isset($argv[1]) ? (int) $argv[1] : 1;
$levelPath = __DIR__ . "/levels/$level.txt";
$replaysDirectoryPath = __DIR__ . '/replays';

// Init dependencies.
$loop = React\EventLoop\Factory::create();
$renderer = new Sokoban\Graphics\Console();

$inputProvider = new \Sokoban\InputProvider\ExternalInput($loop);
$loggingProvider = new \Sokoban\InputProvider\LoggingProvider($inputProvider, $level, $replaysDirectoryPath);

$levelDecoder = new \Sokoban\Loader\PlainDecoder();
$levelLoader = new \Sokoban\Loader\StandardFormatLoader($levelDecoder);

$game = new \Sokoban\Game($loop, $loggingProvider, $renderer, $levelLoader);
$game->loadLevel($levelPath);
$game->run();
