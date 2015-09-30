<?php
namespace Upcast\Meeting\Output;

use Upcast\Meeting\PeriodIterator;
use Upcast\Meeting\NoticeException;
use Upcast\Meeting\ErrorException;
/**
 * File output for meeting
 * Class File
 * @package Upcast\Meeting\Output
 */
class File extends AbstractOutput
{
    /**
     * @param PeriodIterator $iterator
     * @throws NoticeException
     * @throws ErrorException
     */
    public function output(PeriodIterator $iterator)
    {
        $filePath = $this->getConfig()->getOutputFilePath() . '/' . $this->config->_toString() . '.csv';
        if (file_exists($filePath))
        {
            throw new NoticeException("Rodney you plonker, you already ran this. To find what you're looking for take a peek at: \n\n\t" . $filePath);
        }

        $handle = @fopen($filePath, 'w');
        if (!$handle)
        {
            throw new ErrorException('Can\'t establish a handle on the file ' . $filePath);
        }

        while($iterator->valid())
        {
            fputcsv($handle, $iterator->current());
            $iterator->next();
        }

        throw new NoticeException("All done. Check out the file at: \n\n\t" . $filePath);
    }
}