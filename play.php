<?php

require './vendor/autoload.php';

// Init dependencies.
$loop = React\EventLoop\Factory::create();
$inputProvider = new \Sokoban\InputProvider\UserArrows($loop);
$renderer = new Sokoban\Graphics\Console();

// Init game.
$game = new \Sokoban\Game($loop, $inputProvider, $renderer);

// Add player.
$game->addPlayer(new Sokoban\Objects\Player(11, 11, 1, 'Player 1'));

$game->addTarget(new Sokoban\Objects\Target(10, 10));

// Add boxes.
$game->addBox(new Sokoban\Objects\Box(11, 12));
$game->addBox(new Sokoban\Objects\Box(12, 11));

// Add walls.
$game->addWall(new Sokoban\Objects\Wall(0, 1));
$game->addWall(new Sokoban\Objects\Wall(0, 2));
$game->addWall(new Sokoban\Objects\Wall(0, 3));
$game->addWall(new Sokoban\Objects\Wall(0, 4));
$game->addWall(new Sokoban\Objects\Wall(1, 0));
$game->addWall(new Sokoban\Objects\Wall(1, 5));
$game->addWall(new Sokoban\Objects\Wall(2, 1));
$game->addWall(new Sokoban\Objects\Wall(2, 2));
$game->addWall(new Sokoban\Objects\Wall(2, 3));
$game->addWall(new Sokoban\Objects\Wall(2, 4));

$game->run();
