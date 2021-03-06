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
 * @todo Refactor counters to a designated class and just compose them.
 * @author ndobromirov
 */
class GameState
{
    private $moves = null;
    private $pushes = null;
    private $playStart = null;
    private $placedBoxes = null;

    public function __construct()
    {
    }

    public function init()
    {
        if ($this->moves === null) {
            $this->moves = 0;
            $this->pushes = 0;
            $this->placedBoxes = 0;
            $this->playStart = time();
        }
    }

    public function incrementPlacedBoxes()
    {
        ++$this->placedBoxes;
    }

    public function decrementPlacedBoxes()
    {
        --$this->placedBoxes;
    }

    public function getPlacedBoxes()
    {
        return $this->placedBoxes;
    }

    public function incrementPushes()
    {
        ++$this->pushes;
    }

    public function decremetPushes()
    {
        --$this->pushes;
    }

    public function getPushes()
    {
        return $this->pushes;
    }

    public function incrementMoves()
    {
        ++$this->moves;
    }

    public function decrementMoves()
    {
        --$this->moves;
    }

    public function getMoves()
    {
        return $this->moves;
    }

    public function getPlayTime()
    {
        return time() - $this->playStart;
    }
}
