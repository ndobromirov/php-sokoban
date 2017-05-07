<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

use Sokoban\utils\EventsTrait;
use Sokoban\utils\EventAwareInterface;
use Sokoban\InputProvider\ProviderInterface;

/**
 * Description of Base
 *
 * @author ndobromirov
 */
class Base implements EventAwareInterface
{
    use EventsTrait;

    protected static $directions = [
        ProviderInterface::DIRECTION_UP => [0, -1],
        ProviderInterface::DIRECTION_DOWN => [0, 1],
        ProviderInterface::DIRECTION_LEFT => [-1, 0],
        ProviderInterface::DIRECTION_RIGHT => [1, 0],
        ProviderInterface::DIRECTION_NONE => [0, 0],
    ];

    private $row;
    private $col;

    public function __construct($row, $col)
    {
        $this->row = $row;
        $this->col = $col;
    }

    public function getLabel()
    {
        return implode('-', [
            array_reverse(explode('\\', get_class($this)))[0],
            $this->row,
            $this->col,
        ]);
    }

    public function getCoordinates()
    {
        return [$this->row, $this->col];
    }

    public function isMovable()
    {
        return false;
    }
}
