<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Exceptions;

use Sokoban\Objects\Base;

/**
 * Description of CanNotMove
 *
 * @author ndobromirov
 */
class CanNotMove extends GameError
{
    private $object;
    private $target;


    public function __construct(Base $object, Base $target)
    {
        $this->object = $object;
        $this->target = $target;
        $message = "{$object->getLabel()} can not move, because of {$target->getLabel()}!";

        parent::__construct($message);
    }
}
