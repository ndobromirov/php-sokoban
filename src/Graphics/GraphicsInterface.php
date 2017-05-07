<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Graphics;

use Sokoban\GameState;

/**
 *
 * @author ndobromirov
 */
interface GraphicsInterface
{
    public function render(GameState $state, $field);
}
