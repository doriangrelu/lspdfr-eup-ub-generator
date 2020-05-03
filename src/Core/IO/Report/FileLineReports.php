<?php


namespace App\Core\IO\Report;


class FileLineReports
{
    /**
     * @var FileLineReport[]
     */
    private $reports = [];

    /**
     * @param FileLineReport $report
     * @param int $line
     * @return $this
     */
    public function addReport(FileLineReport $report): self
    {
        $this->reports[$report->getLine()] = $report;
        return $this;
    }

    /**
     * @param int $line
     * @return FileLineReport|null
     */
    public function getReportByLine(int $line): ?FileLineReport
    {
        return $this->reports[$line] ?? null;
    }

    public function asError(): bool
    {
        return count(array_filter($this->reports, function (FileLineReport $report) {
                return $report->getNbErrors() > 0;
            })) > 0;
    }

    /**
     * @return FileLineReport[]
     */
    public function getReports(): iterable
    {
        return $this->reports;
    }

}