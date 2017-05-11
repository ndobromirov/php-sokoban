<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Loader;

use Sokoban\Game;

/**
 *
 * @author ndobromirov
 */
interface LevelLoaderInterface
{
    public function load(Game $game, $path);

    public function getRows();
    public function getCollumns();

}
