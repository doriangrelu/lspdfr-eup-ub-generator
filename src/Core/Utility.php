<?php


namespace App\Core;


use Ramsey\Uuid\Uuid;

class Utility
{

    public function uuid(bool $hash = true): string
    {
        return $this->hash(Uuid::uuid1());
    }

    public function hash(string $str): string
    {
        return hash('sha256', $str);
    }

}