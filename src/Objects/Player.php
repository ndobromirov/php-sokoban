<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

use Sokoban\InputProvider\ProviderInterface;

/**
 * Description of Player
 *
 * @author ndobromirov
 */
class Player extends Base
{
    private static $directions = [
        ProviderInterface::DIRECTION_UP => [0, -1],
        ProviderInterface::DIRECTION_DOWN => [0, 1],
        ProviderInterface::DIRECTION_LEFT => [-1, 0],
        ProviderInterface::DIRECTION_RIGHT => [1, 0],
        ProviderInterface::DIRECTION_NONE => [0, 0],
    ];

    public function __construct()
    {
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
}
