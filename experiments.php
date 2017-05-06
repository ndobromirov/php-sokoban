<?php

require './vendor/autoload.php';

system('stty -icanon -echo');

$loop = React\EventLoop\Factory::create();

$stdin = fopen('php://stdin', 'r');
stream_set_blocking($stdin, 0);


$loop->addReadStream($stdin, function ($stdin) {
    $key = ord(fgetc($stdin));

    if (27 === $key) {
        fgetc($stdin);
        $key = ord(fgetc($stdin));
    }

    
    switch ($key) {
        case 65: case ord('8'): echo 'up';      break;
        case 66: case ord('2'): echo 'down';    break;
        case 68: case ord('4'): echo 'left';    break;
        case 67: case ord('6'): echo 'right';   break;

        // TODO: why this...
        case 0: case ord(''): exit(0); break;
    }
});

$loop->run();
