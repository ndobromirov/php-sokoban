<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Loader;

/**
 * Description of PlainDecoder
 *
 * @author ndobromirov
 */
class PlainDecoder implements DecoderInterface
{
    public function decode($rawLevel)
    {
        return explode(PHP_EOL, $rawLevel);
    }
}
