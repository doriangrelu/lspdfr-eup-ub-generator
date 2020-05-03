<?php


namespace App\Core\Interfaces;


interface ComparableInterface
{

    public function equals(object $object): bool;

}