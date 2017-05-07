<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban;

use React\EventLoop\LoopInterface;
use Sokoban\InputProvider\ProviderInterface;
use Sokoban\Objects\Player;
use Sokoban\Objects\NullObject;
/**
 * Description of Game
 *
 * @author ndobromirov
 */
class Game
{
    /** @var LoopInterface */
    private $loop;

    /** @var ProviderInterface */
    private $inputProvider;

    private $width;
    private $height;

    private $walls = [];
    private $boxes = [];
    private $players = [];
    private $targets = [];

    private $field = [];

    private $graphics;

    public function __construct(LoopInterface $loop, ProviderInterface $inputProvider)
    {
        $this->loop = $loop;
        $this->inputProvider = $inputProvider;

        $this->width  = (int) exec('tput cols');
        $this->height = (int) exec('tput lines') - 1;

        $this->field = [];
        for ($row = 0; $row < $this->height; ++$row) {
            $this->field[$row] = [];
            for ($col = 0; $col < $this->width; ++$col) {
                $this->field[$row][$col] = new NullObject();
            }
        }
    }

    public function run()
    {
        // Stop printing of controll characters in UNIX console.
        system('stty -icanon -echo');

        // Start the game loop.
        $this->loop->addPeriodicTimer(0.05, function() {
            $this->update();
            $this->render();
        });

        $this->loop->run();
    }


    public function addObject(Objects\Base $gameObject, $row, $col)
    {
        if ($gameObject instanceof Player) {
            $gameObject->on('move', function($player, $direction) {
                $this->handlePlayerMove($player, $direction);
            });
            $this->players[] = $gameObject;
        }

        $this->field[$row][$col] = $gameObject;
    }

    public function addPlayer(Player $player)
    {

        $this->players[$player->getId] = $player;
    }

    private function handlePlayerMove(Player $player, $direction)
    {

    }
}
