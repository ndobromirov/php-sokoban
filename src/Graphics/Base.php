<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Graphics;

use Sokoban\Objects\Base as GameObject;

/**
 * Description of Base
 *
 * @author ndobromirov
 */
abstract class Base implements GraphicsInterface
{
    protected $buffer;

    public function init()
    {
    }

    public function render($field)
    {
        $this->buffer = $this->initBuffer(count($field), count($field[0]));

        foreach ($field as $row => $rowData) {
            foreach ($rowData as $col => $gameObject) {
                $this->renderGameObject($gameObject, $row, $col);
            }
        }

        $this->displayBuffer();
    }


    abstract protected function initBuffer($rows, $cols);
    abstract protected function renderGameObject(GameObject $object, $row, $col);
    abstract protected function displayBuffer();

}
