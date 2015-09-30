<?php
namespace UpcastTests\Meeting;

use UpcastTests\UpcastTestCase;
use Upcast\Meeting\ScheduleConfig;
use Upcast\Meeting\Schedule;
/**
 * Class ScheduleTest
 * @package UpcastTests\Meeting
 */
class ScheduleTest extends UpcastTestCase
{

    public function testClassConstruct()
    {
        new Schedule(new ScheduleConfig());
    }

    public function testExecutionToStdout()
    {
        $config = new ScheduleConfig();
        $config->setOutput('Stdout');
        $schedule = new Schedule($config);
        ob_start();
        $schedule->execute();
        $response = ob_get_clean();
        $this->assertNotEmpty($response);
    }
}