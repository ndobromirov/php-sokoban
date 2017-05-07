<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Graphics;

use Sokoban\Objects\Base as GameObject;

/**
 * Description of Renderer
 *
 * @author ndobromirov
 */
class Console extends Base
{
    private $mapping;

    public function __construct()
    {
        $this->mapping = [
            \Sokoban\Objects\Wall::class => '#',
            \Sokoban\Objects\Target::class => 'X',
            \Sokoban\Objects\NullObject::class => ' ',
            \Sokoban\Objects\Box::class => 'O',
            \Sokoban\Objects\Player::class => '@',
            \Sokoban\Objects\PlacedBox::class => '0',
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
        $empty = $this->mapping[\Sokoban\Objects\NullObject::class];
        $this->buffer = array_pad([], $rows, str_pad('', $cols, $empty));
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
        $this->buffer[$row][$col] = $this->mapping[get_class($object)];
    }

    private function clearScreen()
    {
        system('clear');
    }
}
