<?php


namespace App\Core\IO\Uploader;


use App\Core\Utility;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{


    /**
     * @var string
     */
    private $storageDirectory;


    /**
     * FileUploader constructor.
     * @param string $storageDirectory
     */
    public function __construct(string $storageDirectory)
    {
        $this->storageDirectory = $storageDirectory;
        \App\Core\IO\File::createDirectoryIfNotExists($this->storageDirectory);
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadFile(UploadedFile $file): string
    {
        $key = Utility::uuid(true);
        $newFilename = $key . '.tmp';
        $file->move($this->storageDirectory, $newFilename);
        return $key;
    }

    /**
     * @param \App\Core\IO\File $file
     */
    public function deleteFile(\App\Core\IO\File $file): void
    {
        if ($file->fileExists()) {
            $file->deleteIfExists(false);
        }

    }
}