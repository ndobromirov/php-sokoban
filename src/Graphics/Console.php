<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Graphics;

use Sokoban\Objects\Base as GameObject;
use Sokoban\Game;
use Sokoban\GameState;
use Sokoban\Objects\Wall;
use Sokoban\Objects\Target;
use Sokoban\Objects\NullObject;
use Sokoban\Objects\Player;
use Sokoban\Objects\Box;

/**
 * Description of Renderer
 *
 * @author ndobromirov
 */
class Console extends Base
{
    private $mapping;

    protected $scaleMap;

    public function __construct()
    {
        // Handling color in terminal.
        // https://askubuntu.com/questions/558280/changing-colour-of-text-and-background-of-terminal
        $this->mapping = [
            Wall::class => [
                $this->colorize('##', 124, 124),
            ],
            Target::class => [
                $this->colorize('..', 20),
            ],
            NullObject::class => [
                $this->colorize('  '),
            ],
            Box::class => [
                $this->colorize('[]', 220),
                $this->colorize('00', 214),
            ],
            Player::class => [
                $this->colorize('++', 20),
                $this->colorize('^^', 255, 0),
                $this->colorize('vv', 255, 0),
                $this->colorize('<<', 255, 0),
                $this->colorize('>>', 255, 0),
            ],
        ];

        $this->scaleMap = [
            1 => 2,
        ];
    }

    private function colorize($content, $bgColor = null, $textColor = null)
    {
        return implode('', [
            $bgColor !== null ? "\e[48;5;{$bgColor}m" : '',
            $textColor !== null ? "\e[38;5;{$textColor}m" : '',
            $content,
            $bgColor !== null || $textColor !== null ? "\e[0m" : '',
        ]);
    }

    public function init(Game $game, $levelWidth, $levelHeight)
    {
        $this->scaleMap = [1 => 2];
        parent::init($game, $levelWidth, $levelHeight);

        // Stop printing of controll characters in UNIX console.
        system('stty -icanon -echo');

        // Handle screen resizing.
        $game->getLoop()->addPeriodicTimer(0.5, function() {
            $this->refreshScale();
        });

        $this->clearScreen();
    }

    protected function initBuffer()
    {
        $emptyCell = $this->mapping[NullObject::class][0];
        $emptyRow = array_pad([], $this->screenWidth, $emptyCell);
        $emptyField = array_pad([], $this->screenHeight, $emptyRow);

        return $emptyField;
    }

    protected function displayBuffer()
    {
        $this->clearScreen();
        ob_start();
        foreach ($this->buffer as $collumns) {
            foreach ($collumns as $ui) {
                echo $ui;
            }
            echo PHP_EOL;
        }
        echo ob_get_clean();
    }

    protected function renderGameObject(GameObject $object, $row, $col)
    {
        $content = $this->mapping[get_class($object)][$object->getStateIndex()];
        $this->buffer[$row + 1][$col] = $content;
    }

    protected function renderState(GameState $state)
    {
        $lineLength = count($this->buffer[1]);
        $empty = $this->mapping[NullObject::class][0];
        $message = implode('   ', [
            " Moves: {$state->getMoves()}",
            " Pushes: {$state->getPushes()}",
            " Play time: {$state->getPlayTime()}",
            " Placed: {$state->getPlacedBoxes()}",
        ]);

        array_unshift($this->buffer, str_split(str_pad($message, $lineLength, $empty)));
    }

    protected function clearScreen()
    {
        system('clear');
    }

    protected function getScreenWidth()
    {
        return (int) floor(exec('tput cols')) - 10;
    }

    protected function getScreenHeight()
    {
        return (int) exec('tput lines') - 3;
    }
}
