<?php


namespace App\Core\Interfaces;


interface StateObjectInterface
{

    public function getStatusCode(): int;

    public function setStatusCode(int $code): StateObjectInterface;

}