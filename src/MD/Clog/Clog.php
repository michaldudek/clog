<?php
/**
 * Clog is a logger provider that provides special kinds of loggers
 * that just push messages to any other loggers you define and add.
 * 
 * @package Clog
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2014, Michał Dudek
 * @license MIT
 */
namespace MD\Clog;

use Psr\Log\LoggerInterface;

use MD\Clog\Logger;

class Clog
{

    /**
     * A unique ID generated for this instance of Clog that will be passed
     * to all provided loggers and all messages will be tagged with it.
     *
     * Useful for example if you want to filter out logs from a single request.
     * 
     * @var string
     */
    protected $uuid;

    /**
     * Any writers (loggers) that have been registered.
     *
     * They are added to all loggers provided by Clog.
     * 
     * @var array
     */
    protected $writers = array();

    /**
     * Registry of all created logs.
     * 
     * @var array
     */
    protected $loggers = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->uuid = uniqid();
    }

    /**
     * Adds a writer.
     *
     * A writer is a logger to which messages from Clog are pushed.
     *
     * The writer will be added to all registered loggers.
     *
     * @param LoggerInterface $writer Logger to which messages are pushed.
     */
    public function addWriter(LoggerInterface $writer) {
        $this->writers[] = $writer;

        // also add to all already registered loggers
        foreach($this->loggers as $logger) {
            $logger->addWriter($writer);
        }
    }

    /**
     * Provides a logger with the given name.
     *
     * @param  string $name Name of the logger to be provided.
     * @return LoggerInterface
     */
    public function provideLogger($name) {
        if (isset($this->loggers[$name])) {
            return $this->loggers[$name];
        }

        $logger = new Logger($name, $this->uuid);

        // also add all writers
        foreach($this->writers as $writer) {
            $logger->addWriter($writer);
        }

        $this->loggers[$name] = $logger;
        return $logger;
    }

}