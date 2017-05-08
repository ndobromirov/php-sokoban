<?php

require './vendor/autoload.php';

$replayPath = isset($argv[1]) ? $argv[1] : './replays/level-1-sample.rep';

$level = (int) explode('-', $replayPath)[1];
$levelPath = __DIR__ . "/levels/$level.txt";

// Init dependencies.
$loop = \React\EventLoop\Factory::create();
$replayProvider = new \Sokoban\InputProvider\ReplayInputProvider($replayPath);
$renderer = new Sokoban\Graphics\Console();

$game = new \Sokoban\Game($loop, $replayProvider, $renderer);
$game->loadLevel($levelPath);
$game->run();
