<?php

/*
    |--------------------------------------------------------------------------
    | Logger
    |--------------------------------------------------------------------------
    |
    | Function global for generate logs.
    |
    */
function logger($name): App\Shared\Logger
{
    return new App\Shared\Logger($name, CONF_LOGGER_PATH, CONF_LOGGER_STATUS);
}

function configure($namefile)
{
    if (strpos('.php', $namefile) === false) {
        $namefile = $namefile . '.php';
    }

    if (file_exists(__DIR__ . '/' . $namefile)) {
        return include($namefile);
    }
}

function docker_env($dockerenv = "docker.env")
{
    $file_path = __DIR__ . "/../config/{$dockerenv}";

    if (file_exists($file_path)) {
        if ($file = fopen($file_path, "r")) {
            $command = [];
            while (!feof($file)) {
                $line = fgets($file);
                if (trim($line)) {
                    $line = explode('=', $line);
                    $command[$line[0]] = $line[1];
                }
            }
            fclose($file);
            return $command;
        }
    }
    return null;
}
