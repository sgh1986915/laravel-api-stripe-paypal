<?php
function handle_warning_as_error_handler($errno, $errstr, $errfile, $errline, array $errcontext)
{
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
function handle_warning_as_error(){
	set_error_handler('handle_warning_as_error_handler');
}