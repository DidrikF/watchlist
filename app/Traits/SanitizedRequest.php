<?php

namespace App\Traits;

use App\Exceptions\TooManyArgumentsException;

//Overriding the all() method, which we know is called
trait SanitizedRequest{

    private $clean = false;


    public function all(){
        return $this->sanitize(parent::all());
    }


    protected function sanitize(Array $inputs){
        
        if($this->clean){ //on subsequent calls to all(), consider the data sanitized
            return $inputs; 
        }

        if(count($inputs) > 20){
            throw new TooManyArgumentsException; 
        }

        foreach($inputs as $key => $input){
            $inputs[$key] = filter_var($input, FILTER_SANITIZE_STRING);
        }

        $this->replace($inputs);
        $this->clean = true;

        return $inputs;
    }
}