<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Loader;

use Sokoban\Game;
use Sokoban\Objects\Wall;
use Sokoban\Objects\Box;
use Sokoban\Objects\Player;
use Sokoban\Objects\Target;

/**
 * Description of ObjectFacory
 *
 * @author ndobromirov
 */
trait ObjectFacory
{
    protected function createWall(Game $game, $row, $col)
    {
        $game->addWall(new Wall($row, $col));
    }

    protected function createPlayer(Game $game, $row, $col)
    {
        $game->addPlayer(new Player($row, $col));
    }

    protected function createBox(Game $game, $row, $col)
    {
        $game->addBox(new Box($row, $col));
    }

    protected function createGoal(Game $game, $row, $col)
    {
        $game->addTarget(new Target($row, $col));
    }

    protected function createPlayerOnGoal(Game $game, $row, $col)
    {
        $this->createGoal($game, $row, $col);
        $this->createPlayer($game, $row, $col);
    }

    protected function createBoxOnGoal(Game $game, $row, $col)
    {
        $this->createGoal($game, $row, $col);
        $this->createBox($game, $row, $col);
    }

    protected function createEmpty(Game $game, $row, $col)
    {
        // Nothing to do here...
    }
}
