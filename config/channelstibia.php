<?php

/*
|--------------------------------------------------------------------------
| CONFIGURACAO TIBIA CHANNELS
|--------------------------------------------------------------------------
|
| Lista as salas que serao criadas pelo BOT.
| Entrada [%s] => Level.
*/

return [
    'config_channels' => [
        ['channel' => "[csspacer1]» Ally(s) List «", "table" => "ally_channel"],
        ['channel' => "[csspacer1]» Enemy(s) List «", "table" => "enemy_channel"],
        ['channel' => "[csspacer1]» Guild(s) Friend [+%s] «", 'table' => 'friend_high_channel'],
        ['channel' => "[csspacer1]» Guild(s) Friend [-%s] «", 'table' => 'friend_low_channel'],
        ['channel' => "[csspacer1]» Guild(s) Hunted [+%s] «", 'table' => 'hunted_high_channel'],
        ['channel' => "[csspacer1]» Guild(s) Hunted [-%s] «", 'table' => 'hunted_low_channel'],
        ['channel' => '[csspacer1]» Neutral(s) List «', 'table' => 'neutral_channel'],
        ['channel' => '[csspacer1]» Death(s) List «', 'table' => 'death_channel'],
        ['channel' => '[csspacer1]» Claimed List «', 'table' => 'claimed_channel'],
        ['channel' => '[csspacer1]» News «', 'table' => 'news_channel']
    ]
];
