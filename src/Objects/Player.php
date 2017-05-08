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

    private $state;

    public function __construct($row, $col, $id = 1, $name = "name")
    {
        $this->id = $id;
        $this->name = $name;

        $this->state = rand(0, 3);
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
        if ($direction) {
            $this->state = $direction - 1;
        }

        // Make everything move away for us.
        $this->trigger('push', [
            $this,
            $direction,
            $this->getDestination($direction),
        ]);

        // Then move when it's free (if possible).
        parent::move($game, $direction);
    }

    public function init(Game $game)
    {
        // TODO: Move this to the update.
        $game->on('new-input', [$this, 'move']);

        // Maintain field correctness.
        $this->on('after-move', function(Player $player, $oldCoords) use ($game) {
            $game->addObject($player);
            $game->clearPoint($oldCoords);

            $game->getState()->incrementMoves();
        });

        parent::init($game);
    }

    public function getStateIndex()
    {
        return $this->state;
    }
}
