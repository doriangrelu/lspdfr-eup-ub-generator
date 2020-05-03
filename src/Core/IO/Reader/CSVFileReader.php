<?php


namespace App\Core\IO\Reader;


use App\Core\Exceptions\IO\CSVException;
use App\Core\Exceptions\IO\FileNotFoundException;
use App\Core\IO\File;
use App\Core\IO\Interfaces\CSVFileReaderInterface;
use App\Core\IO\Report\FileLineReport;
use App\Core\IO\Report\FileLineReports;

class CSVFileReader
{

    /**
     * @var File
     */
    private $file;

    private $delimiter = ';';


    private $enclosure = '"';

    private $escape = '\\';

    private $headers = [];

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Process CSV File Reading
     *
     * @param CSVFileReaderInterface $lineReader
     * @param bool $readHeaders
     * @param array $headers
     * @return FileLineReports
     * @throws CSVException
     * @throws FileNotFoundException
     * @throws \App\Core\Exceptions\IO\HandleFileException
     * @throws \Exception
     */
    public function process(CSVFileReaderInterface $lineReader, bool $readHeaders = true, array $headers = []): FileLineReports
    {
        if ($this->file->fileExists() === false) {
            throw new FileNotFoundException("Missing file: $this->file");
        }
        $reports = new FileLineReports();
        $handle = $this->file->getHandle('r', false);
        $line = 0;
        $lineStatus = true;
        $this->headers = $headers;
        if ($readHeaders && ($headerRead = $this->readLine($handle)) !== false) {
            $this->headers = $headerRead;
        }
        while (($data = $this->readLine($handle)) !== false && $lineStatus) {
            $line++;
            $report = new FileLineReport($line);
            $line = new CSVLine($this->headers, $data);
            $lineStatus = $lineReader->processLine($line, $report);
            $reports->addReport($report);
        }
        $this->file->close();
        $this->headers = [];
        return $reports;
    }

    private function readLine($handle)
    {
        return fgetcsv($handle, 0, $this->delimiter, $this->enclosure, $this->escape);
    }

}