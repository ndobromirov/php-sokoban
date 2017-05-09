<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\InputProvider;

use React\EventLoop\LoopInterface;

/**
 * Description of UserNumPad
 *
 * @author ndobromirov
 */
class UserNumPad extends BaseProvider
{
    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);
    }

    protected function getKeysMapping()
    {
        return  [
            ord('8') => ProviderInterface::DIRECTION_UP,
            ord('2') => ProviderInterface::DIRECTION_DOWN,
            ord('4') => ProviderInterface::DIRECTION_LEFT,
            ord('6') => ProviderInterface::DIRECTION_RIGHT,
            ord('0') => ProviderInterface::DIRECTION_BACK,
        ];
    }
}
