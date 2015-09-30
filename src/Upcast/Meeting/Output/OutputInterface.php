<?php
namespace Upcast\Meeting\Output;

use Upcast\Meeting\PeriodIterator;
use Upcast\Meeting\ScheduleConfig;

/**
 * Contract for outputting meeting data
 * Interface OutputInterface
 */
interface OutputInterface
{

    /**
     * Construct an output adapter.
     * @param ScheduleConfig $config
     */
    public function __construct(ScheduleConfig $config);

    /**
     * @param PeriodIterator $iterator
     * @return void
     */
    public function output(PeriodIterator $iterator);
}