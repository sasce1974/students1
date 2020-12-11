<?php


namespace Core;


class Collection
{
    private static $collection = [];

    public static function add($object){
        self::$collection[] = $object;
    }

    public static function getCollection(){
        return self::$collection;
    }

    public function toArray(){
        $array = [];
        foreach (self::$collection as $object){
            $array[] = (array)$object;
        }
        self::$collection = $array;
        return $this;
    }

    public function getFirst(){
        self::$collection = self::$collection[0];
        return $this;
    }
}