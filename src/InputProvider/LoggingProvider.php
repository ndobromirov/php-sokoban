<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\InputProvider;

use Sokoban\Game;
use Sokoban\GameState;

/**
 * Description of LoggingProvider
 *
 * @author ndobromirov
 */
class LoggingProvider implements ProviderInterface
{
    private $provider;
    private $level;
    private $dir;

    private $moves;
    private $map;

    public function __construct(ProviderInterface $provider, $level, $replaysDir)
    {
        $this->provider = $provider;
        $this->level = $level;
        $this->dir = $replaysDir;
    }

    public function init(Game $game)
    {
        $this->moves = [];
        $this->map = [
            self::DIRECTION_DOWN => 'd',
            self::DIRECTION_UP => 'u',
            self::DIRECTION_LEFT => 'l',
            self::DIRECTION_RIGHT => 'r',
        ];

        // Proxy the call to the original.
        $this->provider->init($game);

        // Handle game success by storing the moves to a file.
        $game->on('level-completed', function(GameState $state) {
            $name = "level-{$this->level}-" . date("Ymd-his") . ".txt";
            file_put_contents("{$this->dir}/$name", implode('', $this->moves));
        });
    }

    public function getLastDirection()
    {
        $result = $this->provider->getLastDirection();
        if ($result !== self::DIRECTION_NONE) {
            $this->moves[] = $this->map[$result];
        }
        return $result;
    }
}
