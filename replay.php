<?php

require './vendor/autoload.php';

$replayPath = isset($argv[1]) ? $argv[1] : './replays/level-1-sample.txt';

$level = (int) explode('-', $replayPath)[1];
$levelPath = __DIR__ . "/levels/$level.txt";

// Init dependencies.
$loop = \React\EventLoop\Factory::create();
$replayProvider = new \Sokoban\InputProvider\ReplayInputProvider($replayPath);
$renderer = new \Sokoban\Graphics\Console();

$levelDecoder = new \Sokoban\Loader\PlainDecoder();
$levelLoader = new \Sokoban\Loader\StandardFormatLoader($levelDecoder);

$game = new \Sokoban\Game($loop, $replayProvider, $renderer, $levelLoader);
$game->loadLevel($levelPath);
$game->run();
