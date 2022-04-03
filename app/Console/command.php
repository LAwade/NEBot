<?php

require_once __DIR__ . "/../../config/includes.php";

$idbot = $argv[1] ?? $CONF_BOT_SERVER_ID;

if ($idbot) {
    echo $idbot;
    $command = new App\Consumers\CommandBot();
    $command->start($idbot);
}
