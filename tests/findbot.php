<?php 
require_once __DIR__ . "/../config/includes.php";

use App\Cases\MongoDBCase;
use App\Providers\MongoDBProvider;

$mongo = new MongoDBCase(new MongoDBProvider());
$mongo->connect('admin', 'Hunt3r195', '165.22.3.4:27017', 'tibiabot', 'admin');

echo "\n-----------------------------------------------------\n";
$guilds = $mongo->find(['guild' => [ '$in' => ['Full Hands']] ], 'guilds');
foreach($guilds->toArray() as $g){
    print_r(formatData($g));
}
echo "\n-----------------------------------------------------\n";

// $levelup = $mongo->findOne(['server' => 'Underwar'], 'levelups');
// print_r(formatData($levelup));

function formatData($data){
    $playerOnline = [];
    foreach($data['data'] as $player){
        array_push($playerOnline, $player['name']); 
    }
    return $playerOnline;
}

