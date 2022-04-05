<?php

require_once __DIR__ . "/../../config/includes.php";

use React\EventLoop\Loop;
use App\Consumers\CommandBot;

$idbot = $argv[1] ?? getenv('CONF_BOT_SERVER_ID');

$timer = Loop::addPeriodicTimer(60, function () use ($idbot) {
    $command = new CommandBot();
    $command->start($idbot);
});
