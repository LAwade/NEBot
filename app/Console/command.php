<?php

ob_flush();
require_once __DIR__ . "/../../config/includes.php";

use App\Consumers\CommandBot;

$idbot = getenv('CONF_BOT_SERVER_ID');

$command = new CommandBot();
$command->start($idbot);
