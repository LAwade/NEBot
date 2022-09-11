<?php

require_once __DIR__ . "/../config/includes.php";

use App\Providers\MongoDBProvider;

$mg = new MongoDBProvider();
$mg->connection(CONF_MONGODB_USER, CONF_MONGODB_PASSWD, CONF_MONGODB_HOST, CONF_MONGODB_COLLECTIONS, CONF_MONGODB_SOURCE);
$mg->collection('tibiabot', 'neutrals');
$neutrals = $mg->findOne(['server' => "Kaldrox"]);

$neutrals = json_decode(json_encode($neutrals), true);

usort($array['data'], function($a, $b){
    return $a['level'] < $b['level'];
});


print_r($array);
?>