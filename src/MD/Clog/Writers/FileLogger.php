<?php
/**
 * Push log messages to file.
 * 
 * @package Clog
 * @subpackage Writers
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2014, Michał Dudek
 * @license MIT
 */
namespace MD\Clog\Writers;

use Psr\Log\LogLevel;

use MD\Clog\Writers\AbstractWriter;

class FileLogger extends AbstractWriter
{

    /**
     * Log file path.
     * 
     * @var string
     */
    protected $filePath;

    /**
     * File handle to which to write the log.
     * 
     * @var resource
     */
    protected $fileHandle;

    /**
     * Constructor.
     * 
     * @param string $filePath Path to the file where the log should be written.
     * @param string $level    [optional] Minimum log level threshold. Default: LogLevel::DEBUG.
     */
    public function __construct($filePath, $level = LogLevel::DEBUG) {
        $this->filePath = $filePath;
        $this->setLevelThreshold($level);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array()) {
        if (!$this->isInLogThreshold($level)) {
            return;
        }

        $fileHandle = $this->provideFileHandle();

        $date = isset($context['_date']) && $context['_date'] instanceof \DateTime
            ? $context['_date']
            : new \DateTime();
        $message = '['. $date->format('Y.m.d H:i:s', $date) .'] '. mb_strtoupper($level) .' : '. $message;

        fwrite($fileHandle, $message ."\n");
    }

    /**
     * Provides a file handle to write to.
     * 
     * @return resource
     */
    protected function provideFileHandle() {
        if (is_resource($this->fileHandle)) {
            return $this->fileHandle;
        }

        if (!is_file($this->filePath)) {
            touch($this->filePath);
        }

        $this->fileHandle = fopen($this->filePath, 'a');
        return $this->fileHandle;
    }

    /**
     * Returns path to the log file.
     * 
     * @return string
     */
    public function getFilePath() {
        return $this->filePath;
    }
    
} 