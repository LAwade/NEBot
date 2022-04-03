<?php

require_once __DIR__ . "/../../config/includes.php";

$server = docker_env();
$idbot = $server['SERVER_ID'];

if ($idbot) {
    $command = new App\Consumers\CommandBot();
    $command->start($idbot);
}
