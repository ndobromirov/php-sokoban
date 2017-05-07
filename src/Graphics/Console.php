<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Graphics;

use Sokoban\Objects\Base as GameObject;
use Sokoban\GameState;

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
        $this->mapping = [
            \Sokoban\Objects\Wall::class => ['#'],
            \Sokoban\Objects\Target::class => ['X'],
            \Sokoban\Objects\NullObject::class => [' '],
            \Sokoban\Objects\Box::class => ['O', '0'],
            \Sokoban\Objects\Player::class => ['@'],
        ];
    }

    public function init()
    {
        // Stop printing of controll characters in UNIX console.
        system('stty -icanon -echo');
        $this->clearScreen();
    }


    protected function initBuffer($rows, $cols)
    {
        $empty = $this->mapping[\Sokoban\Objects\NullObject::class][0];
        $this->buffer = array_pad([], $rows, str_pad('', $cols, $empty));

        // TODO mae it dynamically scalable.
//        $this->screenWidth = (int) exec('tput cols');
//        $this->screenHeight = (int) exec('tput lines');
    }

    protected function displayBuffer()
    {
        $this->clearScreen();
        echo implode(PHP_EOL, array_map([$this, 'joinRow'], $this->buffer));
    }

    private function joinRow($row)
    {
        return implode('', $row);
    }

    protected function renderGameObject(GameObject $object, $row, $col)
    {
        $ui = $this->mapping[get_class($object)];
        $this->buffer[$row + 1][$col] = $ui[$object->getStateIndex()];
    }

    protected function renderState(GameState $state)
    {
        $lineLength = count($this->buffer[1]);
        $empty = $this->mapping[\Sokoban\Objects\NullObject::class][0];
        $message = implode(', ', [
            "Moves: {$state->getMoves()}",
            "Pushes: {$state->getPushes()}",
            "Play time: {$state->getPlayTime()}",
            "Placed: {$state->getPlacedBoxes()}",
        ]);

        array_unshift($this->buffer, str_split(str_pad($message, $lineLength, $empty)));
    }

    private function clearScreen()
    {
        system('clear');
    }
}
