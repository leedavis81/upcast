<?php

namespace Upcast\Meeting;

class Schedule
{

    /**
     * The schdule configuration object
     * @var ScheduleConfig $configuration
     */
    protected $configuration;

    public function __construct(ScheduleConfig $configuration)
    {
        $this->configuration = $configuration;
    }

    public function execute()
    {
        $outputAdapter = $this->configuration->getOutputAdapterInstance();
        $outputAdapter->output($this->getPeriodIterator());
    }

    protected function getPeriodIterator()
    {
        // Set ourselves to the first of this month, this prevents end of month addition issues. eg Jan 31st + 1 month = March 3rd
        $start = new \DateTime(date('Y-m-') . '1');
        $end = clone $start;
        // Subtract a month from the output
        $end->add(\DateInterval::createFromDateString($this->configuration->getNumberOfMonths() . ' months'));

        return new PeriodIterator($start, $end);
    }
}