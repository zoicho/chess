<?php

namespace Chess\Src\Utils;

class IterationChecker
{

    protected static $iterations = [];

    protected static $limit = 100000;

    public function __construct()
    {

    }

    public static function reset()
    {
        self::$iterations = [];
    }

    public static function check($index, $limit = null, $die = false) {
        $function = $index;
        if(!isset(self::$iterations[$function])) {
            self::$iterations[$function] = 0;
        }

        self::$iterations[$function]++;

        if($limit === null) {
            $limit = self::$limit;
        }

        if(self::$iterations[$function] > $limit) {
            if($die) {
                die('Too many iterations by function: ' . $index);
            } else {
                throw new \Exception('Too many iterations by function: ' . $function);
            }
        }
    }

    /**
     * @return int
     */
    public static function getLimit(): int
    {
        return self::$limit;
    }

    /**
     * @param int $limit
     */
    public static function setLimit(int $limit): void
    {
        self::$limit = $limit;
    }

}
