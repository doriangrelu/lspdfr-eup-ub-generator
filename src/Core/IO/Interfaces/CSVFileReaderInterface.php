<?php


namespace App\Core\IO\Interfaces;


use App\Core\IO\Reader\CSVLine;
use App\Core\IO\Report\FileLineReport;

interface CSVFileReaderInterface
{
    /**
     * Line reading action
     *     - Return false if stop reading file or throw Exception
     *
     * @param CSVLine $line
     * @param FileLineReport $report
     * @return bool
     * @throws \Exception
     */
    public function processLine(CSVLine $line, FileLineReport &$report): bool;
}