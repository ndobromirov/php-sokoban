<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\utils;

/**
 *
 * @author ndobromirov
 */
interface EventAwareInterface
{
    public function on($eventName, $callback);

    public function trigger($eventName, array $arguments = []);
}
