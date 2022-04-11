<?php

ini_set("memory_limit", "512M");

require_once __DIR__ . "/../../config/includes.php";

use React\EventLoop\Loop;
use App\Consumers\CommandBot;

$idbot = getenv('CONF_BOT_SERVER_ID');

$timer = Loop::addPeriodicTimer(10, function () use ($idbot) {
    $command = new CommandBot();
    $command->start($idbot);
});
