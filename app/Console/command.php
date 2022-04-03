<?php

require_once __DIR__ . "/../../config/includes.php";

$idbot = $SERVER_ID;

if ($idbot) {
    $command = new App\Consumers\CommandBot();
    $command->start($idbot);
}
