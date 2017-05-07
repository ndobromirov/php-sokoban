<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

/**
 * Description of Base
 *
 * @author ndobromirov
 */
class Base
{
    private $subscribers = [];

    public function on($eventName, $callback)
    {
        if (!isset($this->subscribers[$eventName])) {
            $this->subscribers[$eventName] = [];
        }
        $this->subscribers[$eventName][] = $callback;
    }

    protected function trigger($eventName, array $arguments)
    {
        if (!isset($this->subscribers[$eventName])) {
            return;
        }
        foreach ($this->subscribers[$eventName] as $callback) {
            call_user_func($callback, $arguments);
        }
    }
}
