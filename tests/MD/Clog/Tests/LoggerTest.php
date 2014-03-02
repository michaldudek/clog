<?php
namespace MD\Clog\Tests;

use Psr\Log\LogLevel;

use MD\Clog\Logger;

/**
 * @coversDefaultClass \MD\Clog\Logger
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{

    public function testPushingToWriters() {
        $logger = new Logger('test', '123');

        $writers = array();
        for ($i = 0; $i <= 3; $i++) {
            $writer = $this->getMock('Psr\Log\LoggerInterface');
            $writer->expects($this->once())
                ->method('log');
            $logger->addWriter($writer);
        }

        $logger->info('Testing');
    }

    public function testPassingProperLevelsToWriters() {
        foreach(array(
            'emergency' => LogLevel::EMERGENCY,
            'alert' => LogLevel::ALERT,
            'critical' => LogLevel::CRITICAL,
            'error' => LogLevel::ERROR,
            'warning' => LogLevel::WARNING,
            'notice' => LogLevel::NOTICE,
            'info' => LogLevel::INFO,
            'debug' => LogLevel::DEBUG
        ) as $method => $level) {
            $logger = new Logger('text', '123');

            $message = 'Calling log "'. $method .'" at level '. $level;

            $writer = $this->getMock('Psr\Log\LoggerInterface');
            $writer->expects($this->atLeastOnce())
                ->method('log')
                ->with($level, $message, $this->anything());
            $logger->addWriter($writer);

            call_user_func_array(array($logger, $method), array($message));
        }
    }

}