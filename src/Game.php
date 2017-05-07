<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban;

use React\EventLoop\LoopInterface;
use Sokoban\InputProvider\ProviderInterface;
use Sokoban\Graphics\Base as Renderer;
use Sokoban\Objects\Player;
use Sokoban\Objects\NullObject;
use Sokoban\Objects\Box;
use Sokoban\Objects\PlacedBox;
use Sokoban\Objects\Target;
use Sokoban\Objects\Wall;

/**
 * Description of Game
 *
 * @author ndobromirov
 */
class Game implements utils\EventAwareInterface
{
    use utils\EventsTrait;

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

    /** @var Renderer */
    private $graphics;

    /** @var GameState */
    private $state;

    public function __construct(LoopInterface $loop, ProviderInterface $inputProvider, Renderer $renderer)
    {
        $this->loop = $loop;
        $this->inputProvider = $inputProvider;
        $this->graphics = $renderer;

        $this->field = [];
        $this->width  = (int) exec('tput cols');
        $this->height = (int) exec('tput lines') - 1;

        $this->state = new GameState($this->loop);
    }

    private function addObject(Objects\Base $object)
    {
        list ($row, $col) = $object->getCoordinates();
        $this->field[$row][$col] = $object;
    }

    protected function init()
    {
        $this->graphics->init();

        // Initialize empty field.
        for ($row = 0; $row < $this->height; ++$row) {
            $this->field[$row] = [];
            for ($col = 0; $col < $this->width; ++$col) {
                $this->addObject(new NullObject($row, $col));
            }
        }

        /* @var $object Objects\Base */

        // TODO: Game / player event bindings.
        foreach ($this->players as $object) {
            $this->addObject($object);
            $this->on('new-input', [$object, 'handleInput']);

            // Maintain field correctness.
            $object->on('after-move', function(Player $player, $oldCoords) {
                $this->addObject($player);
                $this->clearPoint($oldCoords);
            });
        }

        $this->state->init();
    }

    public function clearPoint($point)
    {
        $this->addObject(NullObject::fromPoint($point));
    }

    /**
     *
     * @param array $point Ordered pair [row, col]
     * @return Objects\Base
     */
    private function getObject($point)
    {
        return $this->field[$point[0]][$point[1]];
    }

    public function run()
    {
        $this->init();

        // Start the game loop.
        $this->loop->addPeriodicTimer(0.05, function() {
            $this->update();
            $this->render();
        });

        $this->loop->run();
    }

    public function addPlayer(Player $player)
    {
        $this->players[$player->getId()] = $player;
    }

    protected function update()
    {
        $direction = $this->inputProvider->getLastDirection();
        if ($direction === ProviderInterface::DIRECTION_NONE) {
            // Nothing to update.
            return false;
        }
        $this->trigger('new-input', [$this, $direction]);

        return true;
    }

    protected function render()
    {
        $this->graphics->render($this->field);
    }

//    private function handlePlayerMove(Player $player, $direction)
//    {
//
//    }

    public function isFree($point)
    {
        list ($row, $col) = $point;
        return $this->field[$row][$col] instanceof NullObject;
    }

    public function moveTo($object, $row, $col)
    {

    }
}
