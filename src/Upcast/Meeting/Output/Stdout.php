<?php
namespace Upcast\Meeting\Output;

use Upcast\Meeting\PeriodIterator;

/**
 * Stdout output for meeting
 * Class Stdout
 * @package Upcast\Meeting\Output
 */
class Stdout extends AbstractOutput
{
    /**
     * @param PeriodIterator $iterator
     * @return void
     */
    public function output(PeriodIterator $iterator)
    {
        while($iterator->valid())
        {
            $data = $iterator->current();
            foreach ($data as $column)
            {
                echo $column . "\t\t";
            }
            echo "\n";

            $iterator->next();
        }
    }
}