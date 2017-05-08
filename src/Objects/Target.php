<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

use Sokoban\Game;

/**
 * Description of Target
 *
 * @author ndobromirov
 */
class Target extends Base
{
    public function isSteppable()
    {
        return true;
    }

    public function update(Game $game)
    {
        // Handle existence on field.
        if ($game->getObject($this->getCoordinates()) instanceof NullObject) {
            $game->addObject($this);
        }
    }
}
