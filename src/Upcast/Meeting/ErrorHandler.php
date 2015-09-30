<?php

namespace Upcast\Meeting;

/**
 * General class that takes care of handling our errors
 * Class ErrorHandler
 * @package Upcast\Meeting
 */
class ErrorHandler
{

    /**
     * Handle an error
     * @param \Exception $e
     */
    public function handle(\Exception $e)
    {
        if ($e instanceof NoticeException)
        {
            echo PHP_EOL . "\033[32m " . $e->getMessage() . "\033[0m" .  PHP_EOL . PHP_EOL;
            // Provide a positive integer to enable the help notice
            if ($e->getCode() > 0)
            {
                $this->printHelp();
            }
        } elseif ($e instanceof ErrorException)
        {
            echo PHP_EOL . "\033[31m[ERROR]: " . $e->getMessage() . "\033[0m" .  PHP_EOL . PHP_EOL;
            $this->printHelp();
        } else
        {
            echo PHP_EOL . "\033[1;31m[Unexpected Error]: Sorry but something unexpected occurred. Please report this incident to development \033[0m" .  PHP_EOL . PHP_EOL;
            $this->printHelp();
        }


    }

    /**
     * Print out help information for CLI
     */
    protected function printHelp()
    {
        echo "\033[33m";
        echo <<<EOF

******************************************************************************
**                        UPCAST DEV MEETING SCHEDULE TOOL                  **
**                                                                          **
**                        Build: 0.0.1alpha                                 **
******************************************************************************

Usage: php cli.php [-m]<months> [options]

    -m, --months            Number of months to run the schedule for
    --output                The output type to use. Can be 'File' or 'Stdout'
    --output_folder         The output folder to use relative to the current working directory. Defaults to '.'
    -h, --help              Show this help menu

Examples:

    php cli.php -m6
    php cli.php -m6 --output=Stdout
    php cli.php --months=12 --output=File --output_folder=/output


EOF;
        echo "\033[0m";
    }
}
