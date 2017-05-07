<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\utils;

/**
 * Description of EventsTrait
 *
 * @author ndobromirov
 */
trait EventsTrait
{
    private $subscribers = [];

    public function on($eventName, $callback)
    {
        if (!isset($this->subscribers[$eventName])) {
            $this->subscribers[$eventName] = [];
        }
        $this->subscribers[$eventName][] = $callback;
    }

    // TODO: consider mking this private.
    public function trigger($eventName, array $arguments = [])
    {
        if (isset($this->subscribers[$eventName])) {
            foreach ($this->subscribers[$eventName] as $callback) {
                call_user_func_array($callback, $arguments);
            }
        }
    }
}
