<?php

require './vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$inputProvider = new \Sokoban\InputProvider\UserArrows($loop);

$player = new Sokoban\Objects\Player(0, 0);

$game = new \Sokoban\Game($loop, $inputProvider);
$game->addPlayer($player);
$game->run();
