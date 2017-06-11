<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Sokoban\Loader;

use Sokoban\Game;

/**
 * Sokoban level loader
 *
 * @author ndobromirov
 */
class StandardFormatLoader implements LevelLoaderInterface
{
    use ObjectFacory;

    /** @var DecoderInterface */
    private $decoder;

    /** @var array */
    private $map;

    private $rows, $collumns;

    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;

        // http://sokobano.de/wiki/index.php?title=Level_format#Level
        $this->map = [
            '#' => 'createWall',
            '@' => 'createPlayer',
            '+' => 'createPlayerOnGoal',
            '$' => 'createBox',
            '*' => 'createBoxOnGoal',
            '.' => 'createGoal',
            ' ' => 'createEmpty',
        ];
    }

    public function load(Game $game, $path)
    {
        $contents = file_get_contents($path);
        $rows = array_filter($this->decoder->decode($contents));
        foreach ($rows as $row => $collumns) {
            foreach (str_split(rtrim($collumns)) as $collumn => $code) {
                if (isset($this->map[$code])) {
                    call_user_func([$this, $this->map[$code]], $game, $row, $collumn);
                }
            }
        }

        // Init dimensions.
        $this->rows = count($rows);
        $this->collumns = max(array_map('strlen', $rows));
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getCollumns()
    {
        return $this->collumns;
    }
}
