<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\InputProvider;

use Sokoban\Game;

/**
 * Description of ReplayInputProvider
 *
 * @author ndobromirov
 */
class ReplayInputProvider implements ProviderInterface
{
    private $moves;
    private $replay;
    private $moveIndex;

    public function __construct($replayPath)
    {
        $this->replay = $replayPath;
    }

    public function init(Game $game)
    {
        $this->moveIndex = 0;
        $this->moves = strtr(strtolower(file_get_contents($this->replay)), [
            'u' => self::DIRECTION_UP,
            'd' => self::DIRECTION_DOWN,
            'l' => self::DIRECTION_LEFT,
            'r' => self::DIRECTION_RIGHT,
        ]);
    }

    public function getLastDirection()
    {
        return (int) $this->moves[$this->moveIndex++];
    }
}
