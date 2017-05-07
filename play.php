<?php

require './vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$inputProvider = new \Sokoban\InputProvider\UserArrows($loop);

$renderer = new Sokoban\Graphics\Console();

$game = new \Sokoban\Game($loop, $inputProvider, $renderer);
$game->addPlayer(new Sokoban\Objects\Player(0, 0, 1, 'Player 1'));
$game->run();
