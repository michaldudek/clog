<?php
/**
 * This is a special case logger that doesn't log anything by itself
 * but rather pushes the messages to all loggers that have been
 * added to it.
 * 
 * @package Clog
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2014, Michał Dudek
 * @license MIT
 */
namespace MD\Clog;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

use MD\Foundation\Debug\Timer;
use MD\Foundation\Utils\StringUtils;

class Logger implements LoggerInterface
{

    /**
     * List of all added writers.
     * 
     * @var array
     */
    protected $writers = array();

    /**
     * Name of the logger under which it was registered.
     * 
     * @var string
     */
    protected $name;

    /**
     * A uuid that will be added to $context of all logs.
     * 
     * @var string
     */
    protected $uuid;

    /**
     * Constructor.
     */
    public function __construct($name, $uuid) {
        $this->name = $name;
        $this->uuid = $uuid;
    }

    /**
     * Adds a writer.
     * 
     * @param LoggerInterface $writer Logger to which all logged messages will be pushed.
     */
    public function addWriter(LoggerInterface $writer) {
        $this->writers[] = $writer;
    }

    /**
     * Logs with an arbitrary level.
     *
     * Also adds few items to the context array.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array()) {
        $context = array_merge(array(
            '_name' => $this->name,
            '_channel' => $this->name,
            '_uuid' => $this->uuid,
            '_tags' => isset($context['_tags'])
                ? (is_array($context['_tags']) ? $context['_tags'] : explode(',', trim((string)$context['_tags'])))
                : array(),
            '_level' => $level,
            '_timestamp' => Timer::getMicroTime(),
            '_date' => new \DateTime()
        ), $context);

        $message = StringUtils::interpolate((string)$message, $context);

        foreach($this->writers as $writer) {
            $writer->log($level, $message, $context);
        }
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
    
} 