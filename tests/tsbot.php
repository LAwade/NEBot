<?php

require_once __DIR__ . "/../config/includes.php";

use App\Cases\TibiaBOTCase;
use App\Providers\TibiaProvider;
use App\Providers\TSAdminProvider;
use App\Providers\MessageTSProvider;
use App\Providers\MongoDBProvider;

$bot = new TibiaBOTCase(new TibiaProvider, new TSAdminProvider, new MongoDBProvider, new MessageTSProvider);

$bot->handle();

?>