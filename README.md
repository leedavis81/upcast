Upcast Test
===========

The Upcast development team are required to have a mid month meeting in order to discuss the ongoing improvements and new features to the code base. 
This meeting is planned for the 14th of every month. 
Testing is also done on a monthly basis and should be done on the last day of the month.

Rules
-----

If the 14th falls on a Saturday or Sunday then the mid month meeting should be arranged for the following Monday.

If the testing day falls on a Friday, Saturday or Sunday then testing should be set for the previous Thursday.

With this in mind, design a command line script that will write a CSV to file outputting the columns
 'Month', 'Mid Month Meeting Date' and 'End of Month Testing Date' for the next six months.

We would encourage you to not use any frameworks with this test.

Please ensure all relevant documentation is supplied.


Documentation for use
---------------------

Download with git
`git clone https://github.com/leedavis81/upcast.git`

Or [Via Http](https://github.com/leedavis81/upcast/archive/master.zip)

Navigate to the binary folder (bin) to find the cli.php file, and execute it.

Examples
--------

Show me the help menu

`php cli.php -h`

![Help Menu](https://github.com/leedavis81/upcast/raw/master/example_help.png "Example Help Menu")

Give me a six month meeting schedule

`php cli.php -m6`

Give me a six month meeting schedule and print it to the screen

`php cli.php -m6 --output=Stdout`

Give me a twelve month meeting schedule and save it in the 'meetings' folder

`php cli.php -m6 --output=File --output_folder=meetings`

![Example Run](https://github.com/leedavis81/upcast/raw/master/example_run.png "Example Run")

Running Tests
-------------

Although it's suggested that no frameworks should be used, for testing PHPUnit is required. 
To run the test suite you must first install this dependency. 
To do this, ensure you're in the root folder of the application (upcast) and run the following commands:

`composer install`
`vendor/bin/phpunit`

If you see the green bar, then tests are all passing.


