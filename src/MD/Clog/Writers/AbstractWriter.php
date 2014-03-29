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

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use MD\Foundation\Exceptions\InvalidArgumentException;
use MD\Foundation\Exceptions\NotImplementedException;
use MD\Foundation\LogLevels;

abstract class AbstractWriter extends AbstractLogger implements LoggerInterface
{

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
    public function log($level, $message, array $context = array()) {
        throw new NotImplementedException('You must implement \Psr\Log\LoggerInterface::log() method.');
    }

    /**
     * Checks if the given log level is above the set threshold.
     * 
     * @param  string  $level One of \Psr\Log\LogLevel constants.
     * @return boolean
     */
    public function isInLogThreshold($level) {
        return LogLevels::isHigherLevel($level, $this->levelThreshold, true);
    }

    /**
     * Sets a log level threshold.
     * 
     * @param string $level One of \Psr\Log\LogLevel constants.
     */
    public function setLevelThreshold($level) {
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