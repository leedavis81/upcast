<?php

namespace Upcast\Meeting;

/**
 * Configuration class
 * Class ScheduleConfig
 * @package Upcast\Meeting
 */
class ScheduleConfig
{
    const OUTPUT_FILE  = 'File';
    const OUTPUT_STDOUT  = 'Stdout';

    /**
     * The output method to be used
     * @var string $output
     */
    protected $output;

    /**
     * The output path if going to file
     * @var string $output_file_path
     */
    protected $output_file_path;

    /**
     * @var integer $number_of_months
     */
    protected $number_of_months;


    public function __construct()
    {
        // Set up the defaults
        $this->setNumberOfMonths(6);
        $this->setOutput(self::OUTPUT_FILE);

        $this->setOutputFolder(getcwd());

        if (php_sapi_name() === 'cli')
        {
            try{
                $this->parseCliArguments();
            } catch (\Exception $e)
            {
                echo PHP_EOL . "\033[31m[ERROR]: " . $e->getMessage() . "\033[0m" .  PHP_EOL;
                $this->printHelp();
            }
        }
    }

    /**
     * Set the output mechanism to one of the allowed options
     * @param $output
     * @throws \InvalidArgumentException
     */
    public function setOutput($output)
    {
        $output = ucfirst(strtolower($output));

        if (!defined('self::OUTPUT_' . strtoupper($output)))
        {
            throw new \InvalidArgumentException('The output option must be set to either \'File\' or \'Stdout\'');
        }
        $this->output = $output;
    }

    /**
     * Get the output adapter class name
     * @return string $className
     * @throws \DomainException
     */
    public function getOutputAdapterClass()
    {
        if ($this->output === null)
        {
            throw new \RuntimeException('Output mechanism hasn\'t yet been defined. Please use the setOutput() method.');
        }

        return '\\Upcast\\Meeting\\Output\\' . $this->output;
    }

    /**
     * Get an output adapter class instance
     * @return \Upcast\Meeting\Output\OutputInterface
     */
    public function getOutputAdapterInstance()
    {
        $class = $this->getOutputAdapterClass();
        $adapter = new $class($this);
        return $adapter;
    }

    /**
     * @param string $path
     * @param bool $create - ensure the path if created
     */
    public function setOutputFolder($path, $create = true)
    {
        if ($this->output !== self::OUTPUT_FILE)
        {
            throw new \RuntimeException('Output folder should not be specified unless using the output type "File"');
        }
        if (($realPath = realpath($path)) === false)
        {
            // We're preparing for output to file, ensure the directory is created
            if ($create)
            {
                if (!@mkdir($path, 0777, true))
                {
                    throw new \RuntimeException('Unable to create the given path for file output. Please ensure it\'s correct and has writable permissions');
                }
                $realPath = realpath($path);
            } else
            {
                throw new \RuntimeException('Output path doesn\'t exist');
            }
        }
        $this->output_file_path = $realPath;
    }


    /**
     * Get the output file path.
     * @return string
     */
    public function getOutputFilePath()
    {
        if ($this->output !== self::OUTPUT_FILE)
        {
            throw new \RuntimeException('Output file path cannot be used unless using the output method of "' . self::OUTPUT_FILE . '"');
        }

        if ($this->output_file_path === null)
        {
            throw new \RuntimeException('Output folder path is unknown. Please set using the setOutputFolder() method.');
        }

        return $this->output_file_path;
    }

    /**
     * Get the given number of months
     * @return int
     */
    public function getNumberOfMonths()
    {
        return $this->number_of_months;
    }

    /**
     * Set the number of months to output
     * @param int $number_of_months
     */
    public function setNumberOfMonths($number_of_months)
    {
        $this->number_of_months = (int) $number_of_months;
    }


    /**
     * Parse cli arguments and set configuration from them
     */
    protected function parseCliArguments()
    {
        $shortOptions = 'm::h::';
        $longOptions = array(
            'months:',
            'output:',
            'output_folder:',
            'help::'
        );
        $options = getopt($shortOptions, $longOptions);

        if (isset($options['h']) || isset($options['help']))
        {
            $this->printHelp();
            return;
        }

        if (!isset($options['m']) && !isset($options['months']))
        {
            throw new \Exception('You must supply a months value using either -m or --months');
        }

        if (isset($options['m']) || isset($options['months']))
        {
            $months = (isset($options['m'])) ? $options['m'] : $options['months'];
            if (!is_numeric($months))
            {
                throw new \Exception('The months value provided is not a numeric');
            }
            if ($months < 1)
            {
                throw new \Exception('The months value needs to be greater than zero');
            }
            if ($months > 1200)
            {
                throw new \Exception('You want "' . $months . '" months of output? Come on now, your being a bit silly. How about we try using "1200", Surely ten years will be enough for now?');
            }
            $this->setNumberOfMonths($months);
        }

        if (isset($options['output']))
        {
            $this->setOutput($options['output']);
        }

        if (isset($options['output_folder']))
        {
            $this->setOutputFolder($options['output_folder']);
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