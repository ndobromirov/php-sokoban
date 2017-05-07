<?php

require './vendor/autoload.php';

$level = isset($argv[1]) ? (int) $argv[1] : 1;
$levelPath = __DIR__ . "/levels/$level.txt";

// Init dependencies.
$loop = React\EventLoop\Factory::create();
$inputProvider = new \Sokoban\InputProvider\UserArrows($loop);
$renderer = new Sokoban\Graphics\Console();

$game = new \Sokoban\Game($loop, $inputProvider, $renderer);
$game->loadLevel($levelPath);
$game->run();
