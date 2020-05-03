<?php


namespace App\Core\IO\Report;


class FileLineReport
{

    /**
     * @var int
     */
    private $line;

    /**
     * @var int
     */
    private $nbSuccess = 0;
    /**
     * @var int
     */
    private $nbWarnings = 0;
    /**
     * @var int
     */
    private $nbErrors = 0;
    /**
     * @var int
     */
    private $nbinfos = 0;

    /**
     * @var string
     */
    private $successMessage = '';
    /**
     * @var string
     */
    private $errorsMessage = '';
    /**
     * @var string
     */
    private $warningMessage = '';
    /**
     * @var string
     */
    private $infoMessage = '';

    /**
     * FileLineReport constructor.
     * @param int $line
     */
    public function __construct(int $line)
    {
        $this->line = $line;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @param int $line
     * @return FileLineReport
     */
    public function setLine(int $line): FileLineReport
    {
        $this->line = $line;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbSuccess(): int
    {
        return $this->nbSuccess;
    }

    /**
     * @param int $nbSuccess
     * @return FileLineReport
     */
    public function setNbSuccess(int $nbSuccess): FileLineReport
    {
        $this->nbSuccess = $nbSuccess;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbWarnings(): int
    {
        return $this->nbWarnings;
    }

    /**
     * @param int $nbWarnings
     * @return FileLineReport
     */
    public function setNbWarnings(int $nbWarnings): FileLineReport
    {
        $this->nbWarnings = $nbWarnings;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbErrors(): int
    {
        return $this->nbErrors;
    }

    /**
     * @param int $nbErrors
     * @return FileLineReport
     */
    public function setNbErrors(int $nbErrors): FileLineReport
    {
        $this->nbErrors = $nbErrors;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbinfos(): int
    {
        return $this->nbinfos;
    }

    /**
     * @param int $nbinfos
     * @return FileLineReport
     */
    public function setNbinfos(int $nbinfos): FileLineReport
    {
        $this->nbinfos = $nbinfos;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuccessMessage(): string
    {
        return $this->successMessage;
    }

    /**
     * @param string $successMessage
     * @return FileLineReport
     */
    public function setSuccessMessage(string $successMessage): FileLineReport
    {
        $this->successMessage = $successMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorsMessage(): string
    {
        return $this->errorsMessage;
    }

    /**
     * @param string $errorsMessage
     * @return FileLineReport
     */
    public function setErrorsMessage(string $errorsMessage): FileLineReport
    {
        $this->errorsMessage = $errorsMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getWarningMessage(): string
    {
        return $this->warningMessage;
    }

    /**
     * @param string $warningMessage
     * @return FileLineReport
     */
    public function setWarningMessage(string $warningMessage): FileLineReport
    {
        $this->warningMessage = $warningMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getInfoMessage(): string
    {
        return $this->infoMessage;
    }

    /**
     * @param string $infoMessage
     * @return FileLineReport
     */
    public function setInfoMessage(string $infoMessage): FileLineReport
    {
        $this->infoMessage = $infoMessage;
        return $this;
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function addSuccess(?string $message = null): self
    {
        if ($message !== null) {
            $this->setSuccessMessage($this->getSuccessMessage() . "\n" . $message);
        }

        return $this->setNbSuccess($this->getNbSuccess() + 1);
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function addError(?string $message = null): self
    {
        if ($message !== null) {
            $message .= " \n ";
            $this->setErrorsMessage($this->getErrorsMessage() . $message);
        }
        return $this->setNbErrors($this->getNbErrors() + 1);
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function addWarning(?string $message = null): self
    {
        if ($message !== null) {
            $this->setWarningMessage($this->getWarningMessage() . "\n" . $message);
        }
        return $this->setNbWarnings($this->getNbWarnings() + 1);
    }

    /**
     * @param string|null $message
     * @return $this
     */
    public function addInfo(?string $message = null): self
    {
        if ($message !== null) {
            $this->setInfoMessage($this->getInfoMessage() . "\n" . $message);
        }
        return $this->setNbinfos($this->getNbinfos() + 1);
    }

    /**
     * @param FileLineReport[] $reports
     * @return array
     */
    public static function aggregate(array $reports): array
    {
        $nbSuccess = 0;
        $nbWarning = 0;
        $nbErrors = 0;
        $nbInfos = 0;
        $success = [];
        $warnings = [];
        $errors = [];
        $infos = [];
        foreach ($reports as $report) {
            $nbSuccess += $report->getNbSuccess();
            $nbWarning += $report->getNbWarnings();
            $nbErrors += $report->getNbErrors();
            $nbInfos += $report->getNbinfos();

            $success[] = $report->getSuccessMessage();
            $warnings[] = $report->getWarningMessage();
            $errors[] = $report->getErrorsMessage();
            $infos[] = $report->getInfoMessage();
        }

        return [
            'success (' . $nbSuccess . ')' => empty($success) ? 'ND' : $success,
            'info (' . $nbInfos . ')' => empty($infos) ? 'ND' : $infos,
            'warning (' . $nbWarning . ')' => empty($warnings) ? 'ND' : $warnings,
            'error (' . $nbErrors . ')' => empty($errors) ? 'ND' : $errors,
        ];

    }

    public function toJson()
    {
        return json_encode([
            'success (' . $this->getNbSuccess() . ')' => $this->getSuccessMessage(),
            'info (' . $this->getNbinfos() . ')' => $this->getInfoMessage(),
            'warning (' . $this->getNbWarnings() . ')' => $this->getWarningMessage(),
            'error (' . $this->getNbErrors() . ')' => $this->getErrorsMessage(),
        ]);
    }

}