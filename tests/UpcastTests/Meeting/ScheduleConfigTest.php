<?php
namespace UpcastTests\Meeting;

use UpcastTests\UpcastTestCase;
use Upcast\Meeting\ScheduleConfig;
use Upcast\Meeting\Schedule;
/**
 * Class ScheduleTest
 * @package UpcastTests\Meeting
 */
class ScheduleConfigTest extends UpcastTestCase
{

    public function testClassConstruct()
    {
        new ScheduleConfig();
    }

    public function testSettingMonthPeriod()
    {
        $config = new ScheduleConfig();
        $config->setNumberOfMonths(6);

        $this->assertEquals(6, $config->getNumberOfMonths());
    }

    public function testSettingRelativeOutputPath()
    {
        $config = new ScheduleConfig();
        $config->setOutputFolder('local', false, false);

        $this->assertEquals('local', $config->getOutputFilePath());
    }

    public function testSettingAbsoluteOutputPath()
    {
        $config = new ScheduleConfig();
        $config->setOutputFolder('/temp/local', false, false);

        $this->assertEquals('/temp/local', $config->getOutputFilePath());
    }

    public function testOutputSetting()
    {
        $config = new ScheduleConfig();
        $config->setOutput('Stdout');
        $this->assertInstanceOf('Upcast\\Meeting\\Output\\Stdout', $config->getOutputAdapterInstance());

        $config->setOutput('File');
        $this->assertInstanceOf('Upcast\\Meeting\\Output\\File', $config->getOutputAdapterInstance());
    }

    /**
     * @expectedException \Upcast\Meeting\ErrorException
     */
    public function testInvalidOutputSetting()
    {
        $config = new ScheduleConfig();
        $config->setOutput('Junk123');
    }
}