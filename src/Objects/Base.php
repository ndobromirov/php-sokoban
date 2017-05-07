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
use Sokoban\Game;

/**
 * Description of Base
 *
 * @author ndobromirov
 */
class Base implements EventAwareInterface
{
    use EventsTrait;

    protected static $directions = [
        ProviderInterface::DIRECTION_UP => [-1, 0],
        ProviderInterface::DIRECTION_DOWN => [1, 0],
        ProviderInterface::DIRECTION_LEFT => [0, -1],
        ProviderInterface::DIRECTION_RIGHT => [0, 1],
        ProviderInterface::DIRECTION_NONE => [0, 0],
    ];

    protected $row;
    protected $col;

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

    protected function getDestination($direction, $point = null)
    {
        list($rowDelta, $colDelta) = self::$directions[$direction];
        list ($row, $col) = $point ? $point : $this->getCoordinates();
        return [$row + $rowDelta, $col + $colDelta];
    }

    public function move(Game $game, $direction)
    {
        if (!$this->isMovable()) {
            return;
        }
        $destination = $this->getDestination($direction);
        if ($game->isFree($destination)) {
            $oldCoordinates = $this->getCoordinates();

            $this->trigger('before-move', [$this, $destination]);
            list ($this->row, $this->col) = $destination;
            $this->trigger('after-move', [$this, $oldCoordinates]);
        }
    }
}
