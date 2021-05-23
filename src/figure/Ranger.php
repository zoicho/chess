<?php

namespace Chess\Src\Figure;

use Chess\Src\Grid;
use Chess\Src\GridPosition;
use Chess\Src\GridMoves;
use Chess\Src\Utils\Helpers;

class Ranger implements IFigure
{

    public function getNextMovePositions(GridPosition $position, Grid $grid, GridMoves $gridMoves): array
    {

        $x = Helpers::toInt($position->getX());
        $y = $position->getY();

        $movePaths = [];

        for ($ix = 1; $ix <= Grid::getMaxX(); $ix++) {
            for($iy = 1; $iy <= Grid::getMaxY(); $iy++) {
                $offsetX = $ix - $x;
                $offsetY = $iy - $y;
                if(abs($offsetX) === abs($offsetY) && $offsetX !== 0 && $offsetY !== 0) {
                    $movePaths[] = [$offsetX, $offsetY];
                }
            }
        }

        $newPositions = [];

        foreach ($movePaths as $movePath) {

            $newX = $x + $movePath[0];
            $newY = $y + $movePath[1];

            if($gridMoves->isInHistoryByCoords($newX, $newY)) {
                continue;
            }

            if($grid->isCoordsOutOfTheBox($newX, $newY)) {
                continue;
            }

            $newPositions[] = new GridPosition(Helpers::toAlphabetical($newX), $newY);
        }

        return $newPositions;
    }

}
