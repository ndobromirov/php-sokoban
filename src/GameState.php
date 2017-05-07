<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban;

/**
 * Description of GameState
 *
 * @author ndobromirov
 */
class GameState
{
    /** @var \React\EventLoop\LoopInterface */
    private $loop;

    private $moves = null;
    private $playTime = null;

    public function __construct($loop)
    {
        $this->loop = $loop;
    }

    public function init()
    {
        if ($this->moves === null) {
            $this->moves = 0;
            $this->playTime = 0;

            $this->loop->addPeriodicTimer(1, function() {
                // Track the time passed in gameplay.
                ++$this->playTime;
            });
        }
    }

    public function incrementMoves()
    {
        ++$this->moves;
    }

    public function getMoves()
    {
        return $this->moves;
    }

    public function getPlayTime()
    {
        return $this->playTime;
    }

}
