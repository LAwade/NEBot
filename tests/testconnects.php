<?php
ini_set("memory_limit", "512M");

require_once __DIR__ . "/../config/includes.php";

use App\Providers\TSAdminProvider;
use App\Models\Bot;

$host = "159.203.191.18";
$port = "9991";
$queryport = "10101";
$username = "bot";
$password = "Ue8Li9cP6STo";

$tsAdmin = new TSAdminProvider();
$tibiasv = Bot::join('tibia', 'tibia.fk_id_bot', '=', 'bots.id')
    ->join('tibia_api', 'tibia.fk_id_tibia_api', '=', 'tibia_api.id')
    ->join('tibia_api_server', 'tibia_api.fk_id_tibia_api_server', '=', 'tibia_api_server.id')
    ->join('tibia_channel', 'tibia_channel.fk_id_tibia', '=', 'tibia.id')
    ->join('teamspeaks', 'teamspeaks.fk_id_bot', '=', 'bots.id')
    ->select('*', 'tibia_api_server.host AS server_api_host')
    ->where('tibia.active', 1)
    ->get();

foreach ($tibiasv as $sv) {
    $connect = $tsAdmin->connect($sv->host, $sv->queryport, $sv->querylogin, $sv->querypassword);
    $success = $tsAdmin->selectInstance($sv->port);

    if ($connect && $success) {
        echo "CONNECT\n";
    } else {
        continue;
        //echo "NOT CONNECT\n";
    }
}
