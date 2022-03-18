<?php 
require_once __DIR__ . "/../config/includes.php";

use App\Models\Bot;
use App\Models\Teamspeak;

$bot = new Bot();
$bot->level_tibia = 400;
//$bot->sgid_claimed = 3;
//$bot->sgid_bot = 4;
$bot->limit_friend = 2;
$bot->limit_hunted = 2;
$bot->limit_ally = 30;
$bot->limit_enemy = 30;
//$bot->active = true;
$bot->save();

echo "\n";
echo "-----------------------------------------------------\n";
print_r($bot->id);
echo "-----------------------------------------------------\n";
echo "\n";

$host = "159.203.191.88";
$port = "9991"; 
$queryport = "10101";
$username = "serveradmin"; 
$password = "LTCAl7FIkfzI";

$teamspeak = new Teamspeak();
$teamspeak->host = $host;
$teamspeak->port = $port;
$teamspeak->querylogin = $username;
$teamspeak->querypassword = $password;
$teamspeak->queryport = $queryport;
$teamspeak->fk_id_bot = $bot->id;
$teamspeak->save();