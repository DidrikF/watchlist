<?php

namespace App\Exceptions;

use Exception;

class TooManyArgumentsException extends Exception
{

	// Redefine the exception so message isn't optional
    public function __construct($message = 'Too many arguments in request, preventing unnecessary computaion.', $code = 0, Exception $previous = null) 
    {
        // some code
    
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

}