<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

use Sokoban\Game;

/**
 * Description of Box
 *
 * @author ndobromirov
 */
class Box extends Base
{
    private $placed;

    public function isMovable()
    {
        return true;
    }

    public function update(Game $game)
    {
        $wasPlaced = $this->isPlaced();
        $this->placed = $game->getTarget($this->getId()) !== null;

        if (!$wasPlaced && $this->placed) {
            return $this->trigger('placed', [$this]);
        }

        if ($wasPlaced && !$this->placed) {
            return $this->trigger('displaced', [$this]);
        }
    }

    public function isPlaced()
    {
        return $this->placed;
    }

    public function getStateIndex()
    {
        return (int) (bool) $this->placed;
    }

}
