<?php

namespace Sokoban\InputProvider;

/**
 * Description of UserInput
 *
 * @author ndobromirov
 */
class UserInput
{
    /**
     * @var string Input direction value.
     *   One of ProviderInterface::DIRECTION_* constants.
     */
    public $direction;

    /** @var bool True when the user is reversing a move. */
    public $reverse = false;

    /** @var bool True when the user is reversing a pushing move. */
    public $pull = false;

    public function reversed()
    {
        $this->reverse = !$this->reverse;
    }

    public function __construct($direction)
    {
        $this->direction = $direction;
    }

    /**
     * Favtory.
     *
     * @param string $direction
     * @return static
     */
    public static function create($direction)
    {
        return new self($direction);
    }
}
