<?php


namespace App\Core\Interfaces;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface RestServiceInterface
{
    public static function getDirectory(): array;
}