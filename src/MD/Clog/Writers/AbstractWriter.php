<?php
/**
 * Abstract logger that implements \Psr\Log\LoggerInterface 
 * as well as provides a way to set minimum log level threshold.
 *
 * Created for convienience, it MUST NOT be used for type hinting.
 * 
 * @package Clog
 * @subpackage Writers
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2014, Michał Dudek
 * @license MIT
 */
namespace MD\Clog\Writers;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use MD\Foundation\Exceptions\InvalidArgumentException;

abstract class AbstractWriter implements LoggerInterface
{

    /**
     * Map of log levels and their numeric values.
     * 
     * @var array
     */
    protected $levels = array(
        LogLevel::EMERGENCY => 600,
        LogLevel::ALERT => 550,
        LogLevel::CRITICAL => 500,
        LogLevel::ERROR => 400,
        LogLevel::WARNING => 300,
        LogLevel::NOTICE => 250,
        LogLevel::INFO => 200,
        LogLevel::DEBUG => 100
    );

    /**
     * Current set log level threshold.
     * 
     * @var string
     */
    protected $levelThreshold = LogLevel::DEBUG;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    abstract public function log($level, $message, array $context = array());

    /**
     * Checks if the given log level is above the set threshold.
     * 
     * @param  string  $level One of \Psr\Log\LogLevel constants.
     * @return boolean
     */
    public function isInLogThreshold($level) {
        if (!isset($this->levels[$level])) {
            throw new InvalidArgumentException('one of \Psr\Log\LogLevel constants', $level);
        }

        return $this->levels[$level] >= $this->levels[$levelThreshold];
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     */
    public function emergency($message, array $context = array()) {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     */
    public function alert($message, array $context = array()) {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     */
    public function critical($message, array $context = array()) {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     */
    public function error($message, array $context = array()) {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     */
    public function warning($message, array $context = array()) {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     */
    public function notice($message, array $context = array()) {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     */
    public function info($message, array $context = array()) {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     */
    public function debug($message, array $context = array()) {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Sets a log level threshold.
     * 
     * @param string $level One of \Psr\Log\LogLevel constants.
     */
    public function setLevelThreshold($level) {
        if (!isset($this->levels[$level])) {
            throw new InvalidArgumentException('one of \Psr\Log\LogLevel constants', $level);
        }

        $this->levelThreshold = $level;
    }

    /**
     * Returns the current log level threshold.
     * 
     * @return string
     */
    public function getLevelThreshold() {
        return $this->levelThreshold;
    }

}