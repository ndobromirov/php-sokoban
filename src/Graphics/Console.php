<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Graphics;

use Sokoban\Objects\Base as GameObject;
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

    private $screenWidth;
    private $screenHeight;

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

    public function init()
    {
        // Stop printing of controll characters in UNIX console.
        system('stty -icanon -echo');
        $this->clearScreen();
    }


    protected function initBuffer($rows, $cols)
    {
        $empty = $this->mapping[NullObject::class][0];
        $this->buffer = array_pad([], $rows, array_pad([], $cols, $empty));

        // TODO mae it dynamically scalable.
//        $this->screenWidth = (int) exec('tput cols');
//        $this->screenHeight = (int) exec('tput lines');
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

    private function clearScreen()
    {
        system('clear');
    }




}
