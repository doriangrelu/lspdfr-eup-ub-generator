<?php


namespace App\Core\Api;


use App\Core\Interfaces\Runnable;
use App\Core\IO\File;

class XMLProcessingRunnable implements Runnable
{

    private $file = null;

    public function __construct(string $xmlFilename)
    {
        $this->file = new File($xmlFilename);
    }

    public function run(): bool
    {
        return $this->file->fileExists() === false;
    }
}