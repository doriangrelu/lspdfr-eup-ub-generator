<?php


namespace App\Core\IO;


use App\Core\Exceptions\IO\FileNotFoundException;
use App\Core\Exceptions\IO\HandleFileException;
use App\Core\IO\Interfaces\FileLineReaderInterface;
use App\Core\IO\Report\FileLineReport;
use App\Core\IO\Report\FileLineReports;

class File
{


    /**
     * @var string
     */
    private $filename;

    /**
     * @var null|resource
     */
    private $handle = null;

    /**
     * File constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return bool
     */
    public function fileExists(): bool
    {
        return is_file($this->filename) && file_exists($this->filename);
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return mime_content_type($this->filename);
    }

    public function directoryExists(): bool
    {
        return is_dir($this->filename) && file_exists($this->filename);
    }

    /**
     * Close and unlock file before delete this
     */
    public function deleteIfExists(bool $close = true): void
    {
        if ($this->fileExists()) {
            if ($close) {
                $this->close();
            }
            unlink($this->filename);
        }
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function getContent(): string
    {
        $this->failOnFileNotFound();
        return file_get_contents($this->filename);
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        file_put_contents($this->filename, $content);
        return $this;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function appendContent(string $content): self
    {
        file_put_contents($this->filename, $content, FILE_APPEND);
        return $this;
    }

    /**
     * @param bool $parseCategories
     * @return array
     * @throws FileNotFoundException
     */
    public function parseIni(bool $parseCategories = false): array
    {
        $this->failOnFileNotFound();
        return parse_ini_file($this->filename, $parseCategories);
    }

    /**
     * File reader
     *
     * @param FileLineReaderInterface $fileLineReader
     * @return FileLineReports
     * @throws FileNotFoundException
     * @throws HandleFileException
     */
    public function readLines(FileLineReaderInterface $fileLineReader): FileLineReports
    {
        $fileLineReports = new FileLineReports();
        $this->failOnFileNotFound();
        $this->close();
        $handle = $this->getHandle("w+", false);
        $line = 0;
        $buffer = fgets($handle);
        while (!feof($handle)) {
            $line++;
            $report = new FileLineReport($line);
            $fileLineReader->processLine($buffer, $report);
            $fileLineReports->addReport($report);
            $buffer = fgets($handle);
        }
        $this->close();
        return $fileLineReports;
    }

    /**
     * @return int
     * @throws FileNotFoundException
     */
    public function readPermission(): int
    {
        $this->failOnFileNotFound();
        return substr(sprintf('%o', fileperms($this->filename)), -4);
    }

    /**
     * @throws FileNotFoundException
     * @throws HandleFileException
     */
    public function createFileIfNotExists(): void
    {
        if ($this->fileExists() === false) {
            $this->getHandle('w+', false);
            $this->close();
        }
    }

    /**
     * @param int $mode
     */
    public function chmod(int $mode = 0644, bool $changeOwn = false): void
    {
        if ($this->fileExists() || $this->directoryExists()) {
            chmod($this->filename, $mode);
            if ($changeOwn) {
                chown($this->filename, exec('whoami'));
            }
        }
    }


    /**
     *
     */
    public function unlock(): void
    {
        if ($this->handle !== null) {
            flock($this->handle, LOCK_UN);
        }
    }

    /**
     * @param int $mode
     * @return bool
     */
    public function lock(int $mode = LOCK_SH): bool
    {
        if ($this->handle !== null) {
            return flock($this->handle, $mode);
        }

        return false;
    }

    /**
     * @param string $mode
     * @param bool $lockFile
     * @param int $lockMode
     * @return false|resource
     * @throws FileNotFoundException
     * @throws HandleFileException
     */
    public function getHandle(string $mode = 'r', bool $lockFile = true, int $lockMode = LOCK_SH)
    {
        if ($this->handle !== null) {
            return $this->handle;
        }

        $handle = fopen($this->filename, $mode);
        if ($handle === false) {
            throw new HandleFileException();
        }

        if ($lockFile) {
            if (!$this->lock($lockMode)) {
                throw new HandleFileException("Cannot lock file $this->filename");
            }
        }

        $this->handle = $handle;
        return $handle;
    }

    /**
     *
     */
    public function close(): void
    {
        if ($this->handle !== null) {
            fflush($this->handle);
            $this->unlock();
            fclose($this->handle);
            $this->handle = null;
        }
    }

    /**
     * @throws FileNotFoundException
     */
    private function failOnFileNotFound(): void
    {
        if ($this->fileExists() === false) {
            throw new FileNotFoundException("Missing file: $this->filename");
        }
    }


    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function getExtension(): string
    {
        return $this->getPathInfo(PATHINFO_EXTENSION);
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function getBasename(): string
    {
        return $this->getPathInfo(PATHINFO_BASENAME);
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function getDirname(): string
    {
        return $this->getPathInfo(PATHINFO_DIRNAME);
    }

    /**
     * @return string
     * @throws FileNotFoundException
     */
    public function getFilename(): string
    {
        return $this->getPathInfo(PATHINFO_FILENAME);
    }

    /**
     * @return string
     */
    public
    function __toString()
    {
        return $this->filename;
    }

    /**
     * @param int $filter
     * @return string|string[]
     * @throws FileNotFoundException
     */
    private
    function getPathInfo(int $filter)
    {
        $this->failOnFileNotFound();
        return pathinfo($this->filename, $filter);
    }

    /**
     * @param int $law
     */
    public function createDirectory(int $law = 0740): void
    {
        if ($this->directoryExists() == false) {
            mkdir($this->filename);
        }
        $this->chmod($law);
    }

    /**
     * @param string $directory
     */
    public static function createDirectoryIfNotExists(string $directory): void
    {
        $file = new File($directory);
        $file->createDirectory();
    }


    public static function lookup(string $directoryStorage, \App\Entity\File $fileEntity): self
    {
        $file = new File($directoryStorage . '/' . $fileEntity->getRealName(true));
        if ($file->fileExists() === false) {
            throw new FileNotFoundException("Missing file $file");
        }

        return $file;
    }

}