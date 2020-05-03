<?php


namespace App\Core\Interfaces;


interface DocumentWritterInterface
{
    public function parse(array $objects): string;

    public function handleCreateXMLDocument(): void;
}