<?php

$file_path = __DIR__ . "/../config/docker.env";


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
    print_r($command);
}
