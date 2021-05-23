<?php

namespace Chess\Src\Figure;

use Chess\Src\Grid;
use Chess\Src\GridPosition;
use Chess\Src\GridMoves;

interface IFigure
{

    public function getNextMovePositions(GridPosition $position, Grid $grid, GridMoves $gridMoves);

}
