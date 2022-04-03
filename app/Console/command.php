<?php

require_once __DIR__ . "/../../config/includes.php";

$server = docker_env();
$idbot = $argv[1] ? $argv[1] : $server['SERVER_ID'];

if ($idbot) {
    $command = new App\Consumers\CommandBot();
    $command->start($idbot);
}
