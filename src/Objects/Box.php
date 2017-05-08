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


    public function isPlaced()
    {
        return $this->placed;
    }

    public function getStateIndex()
    {
        return (int) (bool) $this->placed;
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

    public function init(Game $game)
    {
        // React to player pushing.
        $playerPush = function(Player $p, $direction, $point) use ($game) {
            if ($this->getCoordinates() === $point) {
                $this->move($game, $direction);
            }
        };
        foreach ($game->getPlayers() as $player) {
            /* @var $player Objects\Player */
            $player->on('push', $playerPush);
        }

        // Maintain game state.
        $this->on('placed', function(Box $box) use ($game) {
            $game->getState()->incrementPlacedBoxes();
        });
        $this->on('displaced', function(Box $box) use ($game) {
            $game->getState()->decrementPlacedBoxes();
        });

        // Maintain field correctness.
        $this->on('after-move', function(Box $box, $oldCoords) use ($game) {
            $game->addObject($box);
            $game->clearPoint($oldCoords);

            $game->getState()->incrementPushes();
        });

        parent::init($game);
    }
}
