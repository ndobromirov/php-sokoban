<?php

require './vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$inputProvider = new \Sokoban\InputProvider\UserArrows($loop);

$game = new \Sokoban\Game($loop, $inputProvider);
$game->run();
