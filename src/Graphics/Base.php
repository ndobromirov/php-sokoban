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
 * Description of Base
 *
 * @author ndobromirov
 */
abstract class Base implements GraphicsInterface
{
    protected $buffer;
    protected $emptyBuffer;

    // Console dimensions in number of characters.
    protected $screenWidth;
    protected $screenHeight;

    // Level dimensions in number of objects.
    protected $levelWidth;
    protected $levelHeight;

    protected $offsetWidth;
    protected $offsetHeight;

    public function init($levelWidth, $levelHeight)
    {
        $this->levelWidth = $levelWidth;
        $this->levelHeight = $levelHeight;

        $this->screenWidth = $this->getScreenWidth();
        $this->screenHeight = $this->getScreenHeight();

//        die("$this->levelWidth $this->levelHeight $this->screenWidth $this->screenHeight");

        $this->offsetWidth = floor($this->screenWidth / 2) - floor($this->levelWidth / 2);
        $this->offsetHeight = floor($this->screenHeight / 2) - floor($this->levelHeight / 2);

        $this->emptyBuffer = $this->initBuffer();
    }

    public function render(GameState $state, $field)
    {
        $this->buffer = $this->emptyBuffer;

        foreach ($field as $row => $rowData) {
            foreach ($rowData as $col => $gameObject) {
                $this->renderGameObject($gameObject, $this->offsetHeight + $row, $this->offsetWidth + $col);
            }
        }

        $this->renderState($state);

        $this->displayBuffer();
    }

    abstract protected function renderState(GameState $state);
    abstract protected function initBuffer();
    abstract protected function renderGameObject(GameObject $object, $row, $col);
    abstract protected function displayBuffer();

    abstract protected function getScreenHeight();
    abstract protected function getScreenWidth();

}
