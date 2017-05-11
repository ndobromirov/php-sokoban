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
use Sokoban\Objects\Target;
use Sokoban\Objects\Wall;
use Sokoban\Loader\LevelLoaderInterface;

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

    /** @var Loader\LevelLoaderInterface */
    private $levelLoader;

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

    public function __construct(LoopInterface $loop, ProviderInterface $inputProvider, Renderer $renderer, LevelLoaderInterface $loader)
    {
        $this->loop = $loop;
        $this->inputProvider = $inputProvider;
        $this->graphics = $renderer;
        $this->levelLoader = $loader;

        $this->field = [];
        $this->state = new GameState($this->loop);
    }

    public function addObject(Objects\Base $object)
    {
        list ($row, $col) = $object->getCoordinates();
        $this->field[$row][$col] = $object;
    }

    private function objectsGenerator()
    {
        foreach ($this->players as $object) {
            yield $object;
        }

        foreach ($this->targets as $object) {
            yield $object;
        }

        foreach ($this->boxes as $object) {
            yield $object;
        }

        foreach ($this->walls as $object) {
            yield $object;
        }
    }

    /** @return LoopInterface */
    public function getLoop()
    {
        return $this->loop;
    }

    public function getState()
    {
        return $this->state;
    }

    protected function init()
    {
        $width = $this->levelLoader->getCollumns();
        $height = $this->levelLoader->getRows();

        $this->inputProvider->init($this);
        $this->graphics->init($this, $width, $height);

        // Initialize empty field.
        for ($row = 0; $row < $height; ++$row) {
            $this->field[$row] = [];
            for ($col = 0; $col < $width; ++$col) {
                $this->addObject(new NullObject($row, $col));
            }
        }

        // Init game objects.
        foreach ($this->objectsGenerator() as $object) {
            $object->init($this);
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
    public function getObject($point)
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

    public function getPlayers()
    {
        return $this->players;
    }

    public function addWall(Wall $wall) {
        $this->walls[] = $wall;
    }

    public function getWalls()
    {
        return $this->walls;
    }

    public function addBox(Box $box)
    {
        $this->boxes[$box->getId()] = $box;
    }

    public function getBoxes()
    {
        return $this->boxes;
    }

    public function addTarget(Target $target)
    {
        $this->targets[$target->getId()] = $target;
    }

    public function getTarget($id)
    {
        return isset($this->targets[$id]) ? $this->targets[$id] : null;
    }

    public function isFree($point)
    {
        list ($row, $col) = $point;
        return isset($this->field[$row])
            && isset($this->field[$row][$col])
            && $this->field[$row][$col]->isSteppable();
    }

    private function readInput()
    {
        // Handle game state mutations.
        $input = $this->inputProvider->getUserInput();
        if ($input->direction !== ProviderInterface::DIRECTION_NONE) {
            $this->trigger('new-input', [$this, $input]);
        }
    }

    private function update()
    {
        /* @var $object Objects\Base */
        foreach ($this->objectsGenerator() as $object) {
            $object->update($this);
        }

        // Handle game completition.
        $placedBoxesCount = $this->state->getPlacedBoxes();
        $this->completed = count($this->targets) === $placedBoxesCount;
    }

    public function loadLevel($path)
    {
        $this->levelLoader->load($this, $path);
        return $this;
    }
}
