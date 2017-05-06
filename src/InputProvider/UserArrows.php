<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\InputProvider;

use React\EventLoop\LoopInterface;

/**
 * Description of InputProvider
 *
 * @author ndobromirov
 */
class UserArrows extends BaseProvider
{
    public function __construct(LoopInterface $loop)
    {
        parent::__construct($loop);
    }

    protected function getKeysMapping()
    {
        return  [
            65 => ProviderInterface::DIRECTION_UP,
            66 => ProviderInterface::DIRECTION_DOWN,
            68 => ProviderInterface::DIRECTION_LEFT,
            67 => ProviderInterface::DIRECTION_RIGHT,
        ];
    }
}
