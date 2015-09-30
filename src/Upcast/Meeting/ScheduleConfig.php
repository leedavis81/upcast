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

    /**
     * An error handler. Only present when an error has occurred
     * @var ErrorHandler $errorHandler
     */
    protected $errorHandler;

    /**
     * Create a configuration instance
     * @throws ErrorException
     */
    public function __construct()
    {
        // Set up the defaults
        $this->setNumberOfMonths(6);
        $this->setOutput(self::OUTPUT_FILE);
        $this->setOutputFolder(getcwd());

        $this->errors = true;

        if (php_sapi_name() === 'cli' && APPLICATION_ENV !== 'testing')
        {
            try{
                $this->parseCliArguments();
            } catch (\Exception $e)
            {
                $this->triggerError($e);
            }
        }

        // No errors were generated
        $this->errors = false;
    }

    /**
     * Trigger an error. This method creates an errorHandler instance which we can use to check the object state.
     * @param \Exception $e
     */
    public function triggerError(\Exception $e)
    {
        $this->errorHandler = new ErrorHandler();
        $this->errorHandler->handle($e);
    }

    /**
     * Set the output mechanism to one of the allowed options
     * @param $output
     * @throws ErrorException
     */
    public function setOutput($output)
    {
        $output = ucfirst(strtolower($output));

        if (!defined('self::OUTPUT_' . strtoupper($output)))
        {
            throw new ErrorException('The output option must be set to either \'File\' or \'Stdout\'');
        }
        $this->output = $output;
    }

    /**
     * Get the output adapter class name
     * @return string $className
     * @throws ErrorException
     */
    public function getOutputAdapterClass()
    {
        if ($this->output === null)
        {
            throw new ErrorException('Output mechanism hasn\'t yet been defined. Please use the setOutput() method.');
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
     * @param bool $create - create the path
     * @param bool $ensureExists - ensure the path exists (if we don't create it)
     * @throws ErrorException
     */
    public function setOutputFolder($path, $create = true, $ensureExists = true)
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
                    throw new ErrorException('Unable to create the given path for file output. Please ensure it\'s correct and has writable permissions');
                }
                $realPath = realpath($path);
            } elseif ($ensureExists)
            {
                if (realpath($path) === false)
                {
                    throw new ErrorException('Output path doesn\'t exist');
                }
            } else
            {
                // Just set it, if write fails error will bubble up
                $realPath = $path;
            }
        }
        $this->output_file_path = $realPath;
    }


    /**
     * Get the output file path.
     * @return string
     * @throws ErrorException
     */
    public function getOutputFilePath()
    {
        if ($this->output !== self::OUTPUT_FILE)
        {
            throw new ErrorException('Output file path cannot be used unless using the output method of "' . self::OUTPUT_FILE . '"');
        }

        if ($this->output_file_path === null)
        {
            throw new ErrorException('Output folder path is unknown. Please set using the setOutputFolder() method.');
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
     * Has a configuration error occurred
     * @return boolean
     */
    public function hasErrors()
    {
        return $this->errorHandler instanceof ErrorHandler;
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
            throw new NoticeException('Please see information on how to use this tool below', 1);
        }

        if (!isset($options['m']) && !isset($options['months']))
        {
            throw new ErrorException('You must supply a months value using either -m or --months');
        }

        if (isset($options['m']) || isset($options['months']))
        {
            $months = (isset($options['m'])) ? $options['m'] : $options['months'];
            if (!is_numeric($months))
            {
                throw new ErrorException('The months value provided is not a numeric');
            }
            if ($months < 1)
            {
                throw new ErrorException('The months value needs to be greater than zero');
            }
            if ($months > 1200)
            {
                throw new ErrorException('You want "' . $months . '" months of output? Come on now, your being a bit silly. How about we try using "1200", Surely ten years will be enough for now?');
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
     * @return string
     * @throws ErrorException
     */
    public function _toString()
    {
        // Return a unique hash based on config settings. We can then use this to prevent duplication
        return sha1(date('Ymd') . $this->output_file_path . $this->number_of_months . $this->output);
    }
}