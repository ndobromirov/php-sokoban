<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

use Sokoban\Game;
use Sokoban\InputProvider\UserInput;

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

    public function move(Game $game, UserInput $input)
    {
        // Make everything move away for us.
        if ($input->direction && !$input->pull) {
            $this->state = $input->direction;

            $destination = $this->getDestination($input->direction);
            $this->trigger('push', [$this, $input, $destination]);
        }

        // Then move when it's free (if possible).
        $oldPosition = parent::move($game, $input);

        // Make everything move tawards us.
        if ($input->direction && $input->pull) {
            $this->trigger('pull', [$this, $input, $oldPosition]);
        }
    }

    public function init(Game $game)
    {
        // TODO: Move this to the update.
        $game->on('new-input', [$this, 'move']);

        // Maintain field correctness.
        $this->on('after-move', function(Player $player, $oldCoords, UserInput $input) use ($game) {
            $game->addObject($player);
            $game->clearPoint($oldCoords);

            if ($input->reverse) {
                $game->getState()->decrementMoves();
            }
            else {
                $game->getState()->incrementMoves();
            }
        });

        parent::init($game);
    }

    public function update(Game $game)
    {
        $this->state = $game->getTarget(parent::getId()) !== null ? 0 : $this->state;

        parent::update($game);
    }

    public function getStateIndex()
    {
        return $this->state;
    }
}
