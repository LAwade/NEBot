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

$mg->collection('tibiabot', 'neutrals');
$neutrals = $mg->findOne(['server' => 'Kaldrox']);
$neutrals = json_decode(json_encode($neutrals), true);
var_dump(in_array(true, array_map('is_array',$neutrals), true));

$neutrals = order($neutrals, 'level', false);
print_r($neutrals);

function order($data, $indice, $mult, $op = '<')
    {
        $data = json_decode(json_encode($data), true);
        if($mult == true){
            foreach ($data as $k => $v) {
                usort($data[$k]['data'], function ($a, $b) use ($indice, $op) {
                    if($op == '<'){
                        return $a[$indice] < $b[$indice];
                    } else {
                        return $a[$indice] > $b[$indice];
                    }
                });
            }
        } else {
            usort($data['data'], function ($a, $b) use ($indice, $op) {
                if($op == '<'){
                    return $a[$indice] < $b[$indice];
                } else {
                    return $a[$indice] > $b[$indice];
                }
            });
        }
        return $data;
    }
