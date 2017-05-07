<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

use Sokoban\Game;

/**
 * Description of Player
 *
 * @author ndobromirov
 */
class Player extends Base
{
    public $id;
    public $name;

    public function __construct($row, $col, $id, $name)
    {
        $this->id = $id;
        $this->name = $name;

        parent::__construct($row, $col);
    }

    public function getLabel()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function isMovable()
    {
        return true;
    }

    public function move(Game $game, $direction)
    {
        // Make everything move away for us.
        $this->trigger('push', [$this, $direction]);

        // Then move when it's free (if possible).
        parent::move($game, $direction);
    }
}
