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
    private $codingMap;
    private $decodingMap;
    private $reverseMap;
    private $moveCode = null;
    private $reversing;

    public function __construct(ProviderInterface $provider, $level, $replaysDir)
    {
        $this->provider = $provider;
        $this->level = $level;
        $this->dir = $replaysDir;
    }

    public function init(Game $game)
    {
        $this->moves = [];
        $this->codingMap = [
            self::DIRECTION_DOWN => 'd',
            self::DIRECTION_UP => 'u',
            self::DIRECTION_LEFT => 'l',
            self::DIRECTION_RIGHT => 'r',
        ];
        $this->decodingMap = array_flip($this->codingMap);

        $this->reverseMap = [
            self::DIRECTION_UP => self::DIRECTION_DOWN,
            self::DIRECTION_DOWN => self::DIRECTION_UP,
            self::DIRECTION_LEFT => self::DIRECTION_RIGHT,
            self::DIRECTION_RIGHT => self::DIRECTION_LEFT,
        ];

        // Proxy the call to the original.
        $this->provider->init($game);

        // Set case to upper, for when the action was moving a box.
        foreach ($game->getBoxes() as $box) {
            $box->on('after-move', function() {
                $this->moveCode = strtoupper($this->moveCode);
            });
        }

        // Add the code to the log, when player moves.
        $playerMoveHandler = function() {
            if (!$this->reversing) {
                $this->moves[] = $this->moveCode;
            }
        };
        foreach ($game->getPlayers() as $player) {
            $player->on('after-move', $playerMoveHandler);
        }

        // Handle game success by storing the moves to a file.
        $game->on('level-completed', function(GameState $state) {
            $name = "level-{$this->level}-" . date("Ymd-his") . ".txt";
            file_put_contents("{$this->dir}/$name", implode('', $this->moves));
        });
    }

    public function getUserInput()
    {
        $result = $this->provider->getUserInput();

        if (($this->reversing = $result->direction === self::DIRECTION_BACK)) {
            // No more history (we are in initial state).
            if (($back = array_pop($this->moves)) === null) {
                $result->direction = self::DIRECTION_NONE;
                return $result;
            }

            // Captitals have 6th bit as zero (0).
            $result->reverse = true;
            $result->pull = $back != ($move = strtolower($back));
            $result->direction = $this->reverseMap[$this->decodingMap[$move]];

            return $result;
        }

        // Hijack the input value.
        if ($result->direction !== self::DIRECTION_NONE) {
            $this->moveCode = $this->codingMap[$result->direction];
        }

        return $result;
    }
}
