<?php

class FileLoggerException extends Exception
{
}

/**
 * File logger
 * 
 * Log notices, warnings, errors or fatal errors into a log file.
 * 
 * @author khalid
 */
class FileLogger
{
    /**
     * Holds the file handle.
     * 
     * @var resource
     */
    protected $fileHandle = NULL;

    /**
     * The time format to show in the log.
     * 
     * @var string
     */
    protected $timeFormat = 'Y.m.d - H:i:s';

    /**
     * The file permissions.
     */
    const FILE_CHMOD = 756;

    /**
     * The log message type.
     */
    const INFO = '[INFO]';
    const NOTICE = '[NOTICE]';
    const WARNING = '[WARNING]';
    const ERROR = '[ERROR]';
    const FATAL = '[FATAL]';

    /**
     * Opens the file handle.
     * 
     * @param string $logfile The path to the loggable file.
     */
    public function __construct(string $logfile)
    {
        if ($this->fileHandle == NULL) {
            $this->openLogFile($logfile);
        }
    }

    /**
     * Closes the file handle.
     */
    public function __destruct()
    {
        $this->closeLogFile();
    }

    /**
     * changeFile
     *
     * @param  string $newLogfile The path to the new loggable file.
     * @return void
     */
    public function changeFile(string $newLogfile): void
    {
        if ($this->fileHandle != NULL) {
            $this->closeLogFile();
        }
        $this->openLogFile($newLogfile);
    }

    /**
     * Logs the message into the log file.
     * 
     * @param  string $message     The log message.
     * @param  string    $messageType Optional: urgency of the message.
     * @return void
     */
    public function log(string $message, string $messageType = FileLogger::WARNING): void
    {
        if ($this->fileHandle == NULL) {
            throw new FileLoggerException('Logfile is not opened.');
        }

        if (!is_string($message)) {
            throw new FileLoggerException('$message is not a string');
        }

        if (
            $messageType != FileLogger::NOTICE &&
            $messageType != FileLogger::WARNING &&
            $messageType != FileLogger::ERROR &&
            $messageType != FileLogger::FATAL &&
            $messageType != FileLogger::INFO
        ) {
            throw new FileLoggerException('Wrong $messagetype given.');
        }
        $this->writeToLogFile("[" . $this->getTime() . "]" . $messageType . " - " . $message);
    }

    /**
     * Writes content to the log file.
     * 
     * @param string $message
     * @return void
     */
    private function writeToLogFile(string $message): void
    {
        flock($this->fileHandle, LOCK_EX);
        fwrite($this->fileHandle, $message . PHP_EOL);
        flock($this->fileHandle, LOCK_UN);
    }

    /**
     * Returns the current timestamp.
     * 
     * @return string with the current date
     * @return string
     */
    private function getTime()
    {
        return date($this->timeFormat);
    }

    /**
     * Closes the current log file.
     * @return void
     */
    protected function closeLogFile(): void
    {
        if ($this->fileHandle != NULL) {
            fclose($this->fileHandle);
            $this->fileHandle = NULL;
        }
    }

    /**
     * Opens a file handle.
     * 
     * @param string $logFile Path to log file.
     * @return void
     */
    public function openLogFile(string $logFile): void
    {
        $this->closeLogFile();

        if (!is_dir(dirname($logFile))) {
            if (!mkdir(dirname($logFile), FileLogger::FILE_CHMOD, true)) {
                throw new FileLoggerException('Could not find or create directory for log file.');
            }
        }

        if (!$this->fileHandle = fopen($logFile, 'a+')) {
            throw new FileLoggerException('Could not open file handle.');
        }
    }
}
