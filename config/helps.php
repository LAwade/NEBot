<?php

    /*
    |--------------------------------------------------------------------------
    | Logger
    |--------------------------------------------------------------------------
    |
    | Function global for generate logs.
    |
    */
    function logger($name): App\Shared\Logger {
        return new App\Shared\Logger($name, CONF_LOGGER_PATH, CONF_LOGGER_STATUS);
    }

    function configure($namefile){
        if(strpos('.php', $namefile) === false){
            $namefile = $namefile . '.php';
        } 
        
        if(file_exists(__DIR__ . '/' . $namefile)){
            return include($namefile);
        }
    }