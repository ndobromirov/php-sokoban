<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\InputProvider;

use React\EventLoop\LoopInterface;

/**
 * Description of ExternalInput
 *
 * @author ndobromirov
 */
class ExternalInput extends BaseProvider
{
    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);
    }

    protected function getKeysMapping()
    {
        return  [
            ord('u') => self::DIRECTION_UP,
            ord('d') => self::DIRECTION_DOWN,
            ord('l') => self::DIRECTION_LEFT,
            ord('r') => self::DIRECTION_RIGHT,
            ord('c') => self::DIRECTION_BACK,
        ];
    }
}
