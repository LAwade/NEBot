<?php

require_once __DIR__ . "/../config/includes.php";

use App\Shared\ProcessOS;

$process = new ProcessOS();

$service = array(
    array("service" => "tsbot.php", "pid" => 2, "idbot" => 14),
);
foreach ($service as $proc) {
    $process->task($proc['service']);
    $process->columns($proc['pid'], $proc['idbot']);
    $tasks = $process->find();

    print_r($task);

    // foreach ($tasks as $task) {
    //     $t = explode('|', $task);
    //     $process->kill($t[0]);
    // }
}
