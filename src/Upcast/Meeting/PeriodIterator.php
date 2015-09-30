<?php

namespace Upcast\Meeting;


class PeriodIterator implements \Iterator {

    /**
     * The iterators offset
     * @var int $offset
     */
    private $offset = 0;

    /**
     * The start period
     * @var \DateTime $start
     */
    private $start;

    /**
     * The date cursor
     * @var \DateTime $date_cursor;
     */
    private $date_cursor;

    /**
     * Cached copy of already calculated periods
     * @var array $calculated_periods
     */
    private $calculated_periods;

    /**
     * The end period
     * @var \DateTime $end
     */
    private $end;

    /**
     * Create a period iterator with start / end boundary
     * @param \DateTime $start
     * @param \DateTime $end
     * @throws \Exception
     */
    public function __construct($start, $end)
    {
        if ($start >= $end)
        {
            throw new \Exception('The start period must be before the end');
        }
        $this->start = $start;
        $this->date_cursor = $start;
        $this->end = $end;
        $this->offset = 0;
    }

    /**
     * Get the current calculated period. Results are cached to prevent recalculation
     * @return array
     */
    public function current()
    {
        if (!isset($this->calculated_periods[$this->offset]))
        {
            $this->calculatePeriod();
        }
        return $this->calculated_periods[$this->offset];
    }


    /**
     * Calculate the current period information from the cursor
     */
    protected function calculatePeriod()
    {
        // Keep $start cleanly at the 1st of the month
        $devMeet = clone $this->date_cursor;
        // Force to the 14th
        $devMeet->setDate($devMeet->format('Y'), $devMeet->format('m'), '14');
        if (in_array($devMeet->format('D'), array('Sat', 'Sun')))
        {
            $devMeet->modify('next monday');
        }

        $testMeet = clone $this->date_cursor;
        // Set to the last day
        $testMeet->modify('last day of this month');
        if (in_array($testMeet->format('D'), array('Fr', 'Sat', 'Sun')))
        {
            $testMeet->modify('previous thursday');
        }

        $periods = array(
            ucfirst($this->date_cursor->format('F Y')),
            $devMeet->format('l jS'),
            $testMeet->format('l jS')
        );

        $this->calculated_periods[$this->offset] = $periods;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->date_cursor->add(\DateInterval::createFromDateString('1 month'));
        ++$this->offset;
    }

    /**
     * Get the current key offset
     * @return int
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * Are we done?
     * $return boolean
     */
    public function valid()
    {
        return ($this->end > $this->date_cursor);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->offset = 0;
        $this->date_cursor = $this->start;
        // We could possibly leave this in place to prevent recalculation, But then we're consuming more memory.
        // Memory vs CPU trade off here
        $this->calculated_periods = array();
    }
}