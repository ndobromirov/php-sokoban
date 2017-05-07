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

    const MAX_WIDTH = 40;
    const MAX_HEIGHT = 18;

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

    private $completed;

    public function __construct(LoopInterface $loop, ProviderInterface $inputProvider, Renderer $renderer)
    {
        $this->loop = $loop;
        $this->inputProvider = $inputProvider;
        $this->graphics = $renderer;

        $this->field = [];
        $this->width  = (int) exec('tput cols');
        $this->height = (int) exec('tput lines') - 1;

//        die("$this->width $this->height");

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

        // Attach boxes.
        foreach ($this->boxes as $object) {
            /* @var $object Objects\Box */
            $this->addObject($object);

            foreach ($this->players as $player) {
                /* @var $player Objects\Player */
                $player->on('push', function(Player $p, $direction, $point) use ($object) {
                    if ($object->getCoordinates() === $point) {
                        $object->move($this, $direction);
                    }
                });
            }

            // Maintain field correctness.
            $object->on('after-move', function(Box $box, $oldCoords) {
                $this->addObject($box);
                $this->clearPoint($oldCoords);
                $this->state->incrementPushes();
            });

            $object->on('placed', function(Box $box) {
                $this->state->incrementPlacedBoxes();
            });

            $object->on('displaced', function(Box $box) {
                $this->state->decrementPlacedBoxes();
            });
        }

        foreach ($this->targets as $target) {
            $this->addObject($target);
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
            $this->readInput();

            $this->update();

            // Render the UI.
            $this->graphics->render($this->state, $this->field);

            if ($this->completed) {
                $this->trigger('level-completed', [$this->state]);
                $this->loop->stop();
            }
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
        $this->boxes[$box->getId()] = $box;
    }

    public function addTarget(Target $target)
    {
        $this->targets[$target->getId()] = $target;
    }

    public function getBox($id)
    {
        return isset($this->boxes[$id]) ? $this->boxes[$id] : null;
    }

    public function getTarget($id)
    {
        return isset($this->targets[$id]) ? $this->targets[$id] : null;
    }

    public function isFree($point)
    {
        list ($row, $col) = $point;
        return 0 <= $row && $row < $this->height
            && 0 <= $col && $col < $this->width
            && $this->field[$row][$col]->isSteppable();
    }

    private function readInput()
    {
        // handle game state mutations.
        $direction = $this->inputProvider->getLastDirection();
        if ($direction !== ProviderInterface::DIRECTION_NONE) {
            $this->trigger('new-input', [$this, $direction]);
        }
    }

    private function update()
    {
        // Hndle targets existence.
        foreach ($this->targets as $target) {
            /* @var $target Objects\Base */
            $fieldInstance = $this->getObject($target->getCoordinates());
            if ($fieldInstance instanceof NullObject) {
                $this->addObject($target);
            }
        }

        // Manage boxes state.
        $total = 0;
        foreach ($this->boxes as $box) {
            /* @var $box Box */
            $box->update($this);
            $total += (int) $box->isPlaced();
        }

        // Handle game completition.
        $this->completed = $total === count($this->targets);
    }

    public function loadLevel($path)
    {
        $map = [
            '#' => ['addWall', Wall::class],
            '@' => ['addPlayer', Player::class],
            'O' => ['addBox', Box::class],
            'X' => ['addTarget', Target::class],
        ];

        $levelDefinition = file_get_contents($path);
        foreach (explode(PHP_EOL, $levelDefinition) as $row => $cols) {
            foreach (str_split($cols) as $col => $code) {
                if (isset($map[$code])) {
                    list ($method, $class) = $map[$code];
                    call_user_func([$this, $method], new $class($row, $col));
                }
            }
        }

        return $this;
    }
}
