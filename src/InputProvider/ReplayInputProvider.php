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
        $rawReplayData = file_get_contents($this->replay);
        $this->moves = array_map('intval', explode(',', $rawReplayData));
        $this->moveIndex = 0;
    }

    public function getLastDirection()
    {
        return $this->moves[$this->moveIndex++];
    }
}
