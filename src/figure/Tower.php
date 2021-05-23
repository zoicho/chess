<?php

namespace Chess\Src\Figure;

use Chess\Src\Grid;
use Chess\Src\GridPosition;
use Chess\Src\GridMoves;
use Chess\Src\Utils\Helpers;

class Tower implements IFigure
{

    public function getNextMovePositions(GridPosition $position, Grid $grid, GridMoves $gridMoves): array
    {

        $x = Helpers::toInt($position->getX());
        $y = $position->getY();

        $movePaths = [];

        for ($ix = 1; $ix <= Grid::getMaxX(); $ix++) {
            if($ix === $x) {
                continue;
            }
            $movePaths[] = [$ix - $x, 0];
        }
        for($iy = 1; $iy <= Grid::getMaxY(); $iy++) {
            if($iy === $y) {
                continue;
            }
            $movePaths[] = [0, $iy - $y];
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
