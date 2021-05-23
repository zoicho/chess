<?php

namespace Chess\Src;


use Chess\Src\Figure\IFigure;
use Chess\Src\Figure\Ranger;
use Chess\Src\Utils\Helpers;
use Chess\Src\Utils\IterationChecker;

class Grid
{

    protected static $maxX = 8;
    protected static $maxY = 8;

    protected static $useGlobalMovesHistory = true;

    public function getShortestPath(GridPosition $fromPosition, GridPosition $toPosition, IFigure $figure)
    {
        $this->validatePositions($fromPosition, $toPosition, $figure);

        $gridMoves = new GridMoves();
        $gridMoves->addPositionToHistory($fromPosition);

        $globalMoves = new GridMoves();
        $globalMoves->addPositionToHistory($fromPosition);

        $nextPositions = $figure->getNextMovePositions($fromPosition, $this, $gridMoves);
        $gridMoves->setNextAvailableMoves($nextPositions);

        $nextMoves = [$gridMoves];

        $resultMoves = $this->processPaths($nextMoves, $toPosition, $figure, $globalMoves);

        return $resultMoves->getMovePath();
    }

    /**
     * @param GridMoves[] $nextMoves
     * @param GridPosition $toPosition
     * @param IFigure $figure
     * @param GridMoves $globalMoves
     * @return GridMoves
     * @throws \Exception
     */
    private function processPaths(array $nextMoves,  GridPosition $toPosition, IFigure $figure, GridMoves $globalMoves)
    {

        IterationChecker::check('processPaths',100);

        /** @var GridMoves[] $allNextMoves */
        $allNextMoves = [];

        /** @var GridMoves[] $validMoves */
        $validMoves = [];

        $countAll = 0;

        foreach ($nextMoves as $nextMove) {
            foreach ($nextMove->getNextAvailableMoves() as $nextPosition) {

                $countAll++;

                $newGridMoves = new GridMoves();
                $newGridMoves->setMovesAlready($nextMove->getMovesAlready());
                $newGridMoves->addPositionToHistory($nextPosition);
                if(self::$useGlobalMovesHistory) {
                    $globalMoves->addPositionToHistory($nextPosition);
                }

                if($nextPosition->getX() === $toPosition->getX() && $nextPosition->getY() === $toPosition->getY()) {

                    /** return first valid position */
                    //return $nextMove;

                    /** store valid move for further selection */
                    $validMoves[] = $newGridMoves;
                }

                $nextPositions = $figure->getNextMovePositions($nextPosition, $this, self::$useGlobalMovesHistory ? $globalMoves : $newGridMoves);
                $newGridMoves->setNextAvailableMoves($nextPositions);

                $allNextMoves[] = $newGridMoves;
            }
        }

        /** return shortest path relative to target location */
        if($validMoves) {
            bdump('all valid moves');
            bdump($validMoves);
            return $this->getShortestMovesRelativeToPosition($validMoves, $toPosition);
        }

        return $this->processPaths($allNextMoves, $toPosition, $figure, $globalMoves);
    }

    /**
     * @param GridMoves[] $gridMovesArr
     * @param GridPosition $position
     * @return GridMoves
     * @throws \Exception
     */
    private function getShortestMovesRelativeToPosition($gridMovesArr, $position)
    {
        $targetX = Helpers::toInt($position->getX());
        $targetY = $position->getY();

        $tempMoves = [];
        foreach ($gridMovesArr as $gridMoves) {
            $score = 0;

            foreach ($gridMoves->getMovePath() as $griPosition) {
                $score += abs($targetX - Helpers::toInt($griPosition->getX())) + abs($targetY - $griPosition->getY());
            }

            $tempMoves[] = [
                'moves' => $gridMoves,
                'score' => $score
            ];
        }
        array_multisort(array_map(function($element) {
            return $element['score'];
        }, $tempMoves), SORT_ASC, $tempMoves);

        return array_shift($tempMoves)['moves'];
    }

    public function validatePositions(GridPosition $fromPosition, GridPosition $toPosition, IFigure $figure)
    {
        if(Helpers::toInt($fromPosition->getX()) > self::$maxX || Helpers::toInt($toPosition->getX()) > self::$maxX) {
            throw new \Exception('Position X is out of the box');
        }
        if($fromPosition->getY() > self::$maxX || $toPosition->getY() > self::$maxX) {
            throw new \Exception('Position Y is out of the box');
        }

        if($fromPosition->getX() === $toPosition->getX() && $fromPosition->getY() === $toPosition->getY()) {
            throw new \Exception('Start position cannot be the same as the end one');
        }

        if($figure instanceof Ranger) {
            if(
                (($fromPosition->getY() + Helpers::toInt($fromPosition->getX())) % 2 === 0 &&
                ($toPosition->getY() + Helpers::toInt($toPosition->getX())) % 2 !== 0) ||
                (($fromPosition->getY() + Helpers::toInt($fromPosition->getX())) % 2 !== 0 &&
                ($toPosition->getY() + Helpers::toInt($toPosition->getX())) % 2 === 0)
            ) {
                throw new \Exception('Target position is invalid');
            }
        }

    }

    public function isCoordsOutOfTheBox(int $x,int $y): bool
    {
        if($x < 1 || $x > self::$maxX) {
            return true;
        }
        if($y < 1 || $y > self::$maxY) {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public static function getMaxX(): int
    {
        return self::$maxX;
    }

    /**
     * @return int
     */
    public static function getMaxY(): int
    {
        return self::$maxY;
    }
}