<?php

namespace Chess\Src;


class GridPosition
{
    /**
     * @var string
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;

    public function __construct(string $x, int $y)
    {
        $this->x = mb_strtoupper($x);
        $this->y = $y;
    }

    /**
     * @return string
     */
    public function getX(): string
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }


}
