<?php

require_once __DIR__ . "/../../config/includes.php";

use React\EventLoop\Loop;
use App\Consumers\CommandBot;

var_dump(getenv('CONF_BOT_SERVER_ID'));

$idbot = $argv[1] ?? getenv('CONF_BOT_SERVER_ID');

$timer = Loop::addPeriodicTimer(10, function () use ($idbot) {
    echo $idbot;
    $command = new CommandBot();
    $command->start($idbot);
});
