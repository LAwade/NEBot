<?php

ob_flush();
ini_set("memory_limit", "512M");

require_once __DIR__ . "/../../config/includes.php";

use App\Cases\TibiaBOTCase;
use App\Providers\TibiaProvider;
use App\Providers\TSAdminProvider;
use App\Providers\MessageTSProvider;
use App\Providers\MongoDBProvider;
use React\EventLoop\Loop;

$bot = new TibiaBOTCase(new TibiaProvider, new TSAdminProvider, new MongoDBProvider, new MessageTSProvider);

$timer = Loop::addPeriodicTimer(20, function () use ($bot) {
    $bot->handle();
});
