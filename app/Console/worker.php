<?php 

require_once __DIR__ . "/../../config/includes.php";

use React\EventLoop\Loop;
use App\Providers\TibiaProvider;
use App\Cases\TibiaCase;

$tibia = new TibiaCase(new TibiaProvider);

$timer = Loop::addPeriodicTimer(20, function () use ($tibia) {
    $tibia->handle();
});

?>