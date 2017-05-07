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

    public function move($direction)
    {
        list($newX, $newY) = self::$directions[$direction];
        if ($this->x != $newX || $this->y != $newY) {
            $this->trigger('push', [$this, $direction]);

            $this->x += $newX;
            $this->y += $newY;
        }
    }

    public function handleInput(Game $game, $direction)
    {
        $oldCoordinates = $this->getCoordinates();
        list($rowDelta, $colDelta) = self::$directions[$direction];
        $destination = [$this->row + $rowDelta, $this->col + $colDelta];

        // Make everything move away for us.
        $this->trigger('push', [$this, $direction]);

        if ($game->isFree($destination)) {
            $this->trigger('before-move', [$this, $destination]);
            list ($this->row, $this->col) = $destination;
            $this->trigger('after-move', [$this, $oldCoordinates]);
        }
    }
}
