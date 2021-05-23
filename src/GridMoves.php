<?php

namespace Chess\Src;

use Chess\Src\Utils\Helpers;

class GridMoves
{

    protected $movesAlready = [];

    /** @var GridPosition[] */
    protected $nextAvailableMoves = [];

    public function __construct()
    {

    }

    public function addPositionToHistory(GridPosition $position)
    {
        $index = Helpers::toInt($position->getX()) . '-' . $position->getY();
        $this->movesAlready[$index] = $position;
    }

    public function isInHistoryByCoords(int $x, int $y): bool
    {
        $index = $x . '-' . $y;
        if(array_key_exists($index, $this->movesAlready)) {
            return true;
        }

        return false;
    }

    /**
     * @return GridPosition[]
     */
    public function getMovePath()
    {
        $moves = array_values($this->getMovesAlready());
        array_shift($moves);
        return $moves;
    }

    /**
     * @return array
     */
    public function getMovesAlready(): array
    {
        return $this->movesAlready;
    }

    /**
     * @param array $movesAlready
     */
    public function setMovesAlready(array $movesAlready): void
    {
        $this->movesAlready = $movesAlready;
    }

    /**
     * @return GridPosition[]
     */
    public function getNextAvailableMoves(): array
    {
        return $this->nextAvailableMoves;
    }

    /**
     * @param GridPosition[] $nextAvailableMoves
     */
    public function setNextAvailableMoves(array $nextAvailableMoves): void
    {
        $this->nextAvailableMoves = $nextAvailableMoves;
    }

}
