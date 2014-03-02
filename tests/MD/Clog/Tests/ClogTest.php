<?php
namespace MD\Clog\Tests;

use MD\Clog\Clog;

/**
 * @coversDefaultClass \MD\Clog\Clog
 */
class ClogTest extends \PHPUnit_Framework_TestCase
{

    public function testProvidingLogger() {
        $clog = new Clog();
        $logger = $clog->provideLogger('Logger');
        $this->assertInstanceOf('Psr\Log\LoggerInterface', $logger);
    }

    public function testProvidingDifferentLoggers() {
        $clog = new Clog();
        $loggerOne = $clog->provideLogger('Logger One');
        $loggerTwo = $clog->provideLogger('Logger Two');
        $this->assertNotSame($loggerOne, $loggerTwo);
    }

    public function testProvidingSameLoggers() {
        $clog = new Clog();
        $loggerOne = $clog->provideLogger('Logger');
        $loggerTwo = $clog->provideLogger('Logger');
        $this->assertSame($loggerOne, $loggerTwo);
    }

    public function testAddingWriterToNewLoggers() {
        $clog = new Clog();
        $writer = $this->getMock('Psr\Log\LoggerInterface');
        $writer->expects($this->once())
            ->method('log');

        $clog->addWriter($writer);

        $logger = $clog->provideLogger('Logger');
        $logger->info('Testing this.');
    }

    public function testAddingWriterToExistingLoggers() {
        $clog = new Clog();
        $logger = $clog->provideLogger('Logger');
        
        $writer = $this->getMock('Psr\Log\LoggerInterface');
        $writer->expects($this->once())
            ->method('log');

        $clog->addWriter($writer);

        $logger->info('Testing this.');   
    }

}