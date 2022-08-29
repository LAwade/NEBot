<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

ini_set("memory_limit", "512M");

require_once __DIR__ . "/../config/includes.php";

use React\EventLoop\Loop;
use App\Consumers\CommandBot;

$idbot = '2';

$timer = Loop::addPeriodicTimer(1, function () use ($idbot) {
    echo "Iniciando BOT Servidor: $idbot";
    $command = new CommandBot();
    $command->start($idbot);
});
