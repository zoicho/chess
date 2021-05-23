<?php

namespace Chess\Src\Figure;

use Chess\Src\Grid;
use Chess\Src\GridPosition;
use Chess\Src\GridMoves;
use Chess\Src\Utils\Helpers;

class Horse implements IFigure
{

    public function getNextMovePositions(GridPosition $position, Grid $grid, GridMoves $gridMoves): array
    {

        $x = Helpers::toInt($position->getX());
        $y = $position->getY();

        $movePaths = [
            [-2,-1],
            [-1,-2],
            [1,-2],
            [2,-1],
            [2,1],
            [1,2],
            [-1,2],
            [-2,1],
        ];

        $newPositions = [];

        foreach ($movePaths as $movePath) {

            $newX = $x + $movePath[0];
            $newY = $y + $movePath[1];

            if($gridMoves->isInHistoryByCoords($newX, $newY)) {
                continue;
            }

            if($grid->isPositionOutOfTheBox($newX, $newY)) {
                continue;
            }

            $newPositions[] = new GridPosition(Helpers::toAlphabetical($newX), $newY);
        }

        return $newPositions;
    }

}
