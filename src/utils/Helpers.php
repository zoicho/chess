<?php

namespace Chess\Src\Utils;

class Helpers
{

    private static $alphabeticalMap;

    public static function toAlphabetical(int $number): string
    {
        self::initMap();
        if(!isset(self::$alphabeticalMap[$number - 1])) {
            throw new \Exception('No alphabetical representation found of number ' . $number);
        }
        return self::$alphabeticalMap[$number - 1];
    }

    public static function toInt(string $string): int
    {
        self::initMap();
        $string = mb_strtoupper($string);
        $alphaFound = array_search($string, self::$alphabeticalMap);
        if($alphaFound === false) {
            throw new \Exception('No number representation found of string ' . $string);
        }
        return $alphaFound + 1;
    }

    private static function initMap()
    {
        if(!self::$alphabeticalMap) {
            self::$alphabeticalMap = range('A', 'Z');
        }
    }

}
