<?php
namespace Upcast\Meeting\Output;

use Upcast\Meeting\ScheduleConfig;
/**
 * File output for meeting
 * Class File
 * @package Upcast\Meeting\Output
 */
abstract class AbstractOutput implements OutputInterface
{
    /**
     * The schedule config.
     * Immutable, once constructed this object should not be altered.
     * If settings need to change then a new Schedule object should be created.
     * @var ScheduleConfig $config
     */
    protected $config;

    /**
     * Construct an output adapter.
     * @param ScheduleConfig $config
     */
    public function __construct(ScheduleConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Get the schedule configuration object
     * @return ScheduleConfig
     */
    protected function getConfig()
    {
        return $this->config;
    }
}
