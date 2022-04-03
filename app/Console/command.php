<?php

require_once __DIR__ . "/../../config/includes.php";

$idbot = $argv[1] ?? $DOCKER_SERVER_ID;

if ($idbot) {
    $command = new App\Consumers\CommandBot();
    $command->start($idbot);
}
