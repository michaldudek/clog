<?php
/**
 * Log kept fully in memory.
 * 
 * @package Clog
 * @subpackage Writers
 * @author Michał Dudek <michal@michaldudek.pl>
 * 
 * @copyright Copyright (c) 2014, Michał Dudek
 * @license MIT
 */
namespace MD\Clog\Writers;

use MD\Clog\Writers\AbstractWriter;

class MemoryLogger extends AbstractWriter
{

    /**
     * List of all logged messages.
     * 
     * @var array
     */
    protected $messages = array();

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array()) {
        $this->messages[] = array(
            'msg' => $message,
            'lvl' => $level,
            'context' => $context
        );
    }

    /**
     * Returns all logged messages.
     * 
     * @return array
     */
    public function getMessages() {
        return $this->messages;
    }
    
} 