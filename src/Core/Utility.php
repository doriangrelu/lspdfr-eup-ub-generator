<?php


namespace App\Core;


use Ramsey\Uuid\Uuid;

class Utility
{

    public static function uuid(bool $hash = true): string
    {
        return self::hash(Uuid::uuid1());
    }

    public static function hash(string $str): string
    {
        return hash('sha256', $str);
    }

}