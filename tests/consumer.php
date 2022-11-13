<?php

require_once __DIR__ . "/../config/includes.php";

use App\Providers\MongoDBProvider;

$mg = new MongoDBProvider();
$mg->connection(CONF_MONGODB_USER, CONF_MONGODB_PASSWD, CONF_MONGODB_HOST, CONF_MONGODB_COLLECTIONS, CONF_MONGODB_SOURCE);

// $dataFriends = ['Are You Ready', 'Pull And Kill'];
// $mongo = $mg->collection('tibiabot', 'guilds');
// $datafriends = $mg->find(['guild' => ['$in' => $dataFriends]]);
// $friends = $datafriends->toArray();
// $friends = order($friends, 'level');
// $friends = order($friends, 'vocation');
