<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban;

use React\EventLoop\LoopInterface;
use Sokoban\InputProvider\ProviderInterface;

/**
 * Description of Game
 *
 * @author ndobromirov
 */
class Game
{
    /** @var LoopInterface */
    private $loop;

    /** @var ProviderInterface */
    private $inputProvider;

    public function __construct(LoopInterface $loop, ProviderInterface $inputProvider)
    {
        $this->loop = $loop;
        $this->inputProvider = $inputProvider;
    }

    public function run()
    {
        // Stop printing of controll characters in UNIX console.
        system('stty -icanon -echo');

        $this->loop->run();
    }
}
