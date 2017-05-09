<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\InputProvider;

use React\EventLoop\LoopInterface;
use Sokoban\Game;

/**
 * Description of BaseProvider
 *
 * @author ndobromirov
 */
abstract class BaseProvider implements ProviderInterface
{
    private $lastInput;

    /** @var LoopInterface */
    private $loop;
    private $stream;

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
        $this->stream = $this->getInputStream();
    }

    public function init(Game $game)
    {
        $mapping = $this->getKeysMapping();
        $this->loop->addReadStream($this->stream, function ($stdin) use ($mapping) {
            // Always default input to NONE.
            $this->lastInput = self::DIRECTION_NONE;

            // Read character.
            $key = ord(fgetc($stdin));
            if (27 === $key) {
                // Handle controll characters.
                fgetc($stdin);
                $key = ord(fgetc($stdin));
            }

            // Update only valid input.
            if (isset($mapping[$key])) {
                $this->lastInput = $mapping[$key];
            }
        });
    }

    public function __destruct()
    {
        $this->loop->removeReadStream($this->stream);
        fclose($this->stream);
    }

    public function getUserInput()
    {
        $result = UserInput::create($this->lastInput);
        $this->lastInput = self::DIRECTION_NONE;
        return $result;
    }

    protected function getInputStream()
    {
        $stream = fopen('php://stdin', 'r');
        stream_set_blocking($stream, 0);
        return $stream;
    }

    abstract protected function getKeysMapping();
}
