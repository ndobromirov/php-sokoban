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
        $this->height = (int) exec('tput lines') - 2;

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

        foreach ($this->players as $object) {
            /* @var $object Objects\Player */
            $this->addObject($object);
            $this->on('new-input', [$object, 'move']);

            // Maintain field correctness.
            $object->on('after-move', function(Player $player, $oldCoords) {
                $this->addObject($player);
                $this->clearPoint($oldCoords);
                $this->state->incrementMoves();
            });
        }

        // Attach walls.
        foreach ($this->walls as $object) {
            /* @var $object Objects\Wall */
            $this->addObject($object);
        }

        // Attachc boxes.
        foreach ($this->boxes as $object) {
            /* @var $object Objects\Box */
            $this->addObject($object);

            foreach ($this->players as $player) {
                /* @var $player Objects\Player */
                $player->on('push', function(Player $p, $direction) use ($object) {
                    $object->move($this, $direction);
                });
            }

            // Maintain field correctness.
            $object->on('after-move', function(Box $box, $oldCoords) {
                $this->addObject($box);
                $this->clearPoint($oldCoords);
                $this->state->incrementPushes();
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
            // handle game state mutations.
            $direction = $this->inputProvider->getLastDirection();
            if ($direction !== ProviderInterface::DIRECTION_NONE) {
                $this->trigger('new-input', [$this, $direction]);
            }

            // Render the UI.
            $this->graphics->render($this->state, $this->field);
        });

        $this->loop->run();
    }

    public function addPlayer(Player $player)
    {
        $this->players[$player->getId()] = $player;
    }

    public function addWall(Wall $wall) {
        $this->walls[] = $wall;
    }

    public function addBox(Box $box)
    {
        $this->boxes[] = $box;
    }

    public function isFree($point)
    {
        list ($row, $col) = $point;
        return $row >= 0
            && $col < $this->height
            && $this->field[$row][$col] instanceof NullObject;
    }
}
