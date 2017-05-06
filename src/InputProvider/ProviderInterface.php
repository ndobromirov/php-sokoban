<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\InputProvider;

/**
 *
 * @author ndobromirov
 */
interface ProviderInterface
{
    const DIRECTION_NONE = 0;
    const DIRECTION_UP = 1;
    const DIRECTION_DOWN = 2;
    const DIRECTION_LEFT = 3;
    const DIRECTION_RIGHT = 4;

    public function getLastDirection();
}
