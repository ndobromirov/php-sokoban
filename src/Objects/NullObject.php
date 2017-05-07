<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Objects;

/**
 * Description of NullObject
 *
 * @author ndobromirov
 */
class NullObject extends Base
{
    public static function fromPoint($point)
    {
        list ($row, $col) = $point;
        return new static($row, $col);
    }

    public function isSteppable()
    {
        return true;
    }
}
