<?php

use App\Models\TibiaChannel;
use App\Models\TibiaClaimedPlayer;

require_once __DIR__ . "/../config/includes.php";


$conf = TibiaChannel::where('claimed_channel')->count();

echo "\n-----------------------------------------------------\n";
print_r($conf);
echo "\n-----------------------------------------------------\n";



