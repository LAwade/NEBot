<?php

namespace App\Cases;

use App\Interfaces\ITSAdmin;
use App\Interfaces\IMessage;
use App\Interfaces\IMongoDB;
use App\Interfaces\ITibia;
use App\Models\Bot;
use App\Models\Notification;
use App\Models\TibiaAllyList;
use App\Models\TibiaClaimedCity;
use App\Models\TibiaClaimedPlayer;
use App\Models\TibiaEnemyList;
use App\Models\TibiaFriendList;
use App\Models\TibiaHuntedList;
use Exception;

class TibiaBOTCase
{
    /**
     * Connection with ServerQuery
     * @var ITSAdmin $tsAdmin
     */
    private $tsAdmin;

    /**
     * @var IMessage $message
     */
    private $message;

    /**
     * @var ITibiaBOT $tibiabot
     */
    private $tibiabot;

    private $tibia;

    /**
     * @var IMongoDB $tibiabot
     */
    private $mongodb;

    private $data = [];

    const LIMITE_DESCRIPTION = 90;

    public function __construct(ITibia $tibiabot, ITSAdmin $tsAdmin, IMongoDB $mongodb, IMessage $message)
    {
        $this->tibiabot = $tibiabot;
        $this->tsAdmin = $tsAdmin;
        $this->mongodb = $mongodb;
        $this->message = $message;
        $this->mongodb = $mongodb->connection(CONF_MONGODB_USER, CONF_MONGODB_PASSWD, CONF_MONGODB_HOST, CONF_MONGODB_COLLECTIONS, CONF_MONGODB_SOURCE);
    }

    public function handle()
    {
        try {
            $tibiasv = Bot::join('tibia', 'tibia.fk_id_bot', '=', 'bots.id')
                ->join('tibia_api', 'tibia.fk_id_tibia_api', '=', 'tibia_api.id')
                ->join('tibia_api_server', 'tibia_api.fk_id_tibia_api_server', '=', 'tibia_api_server.id')
                ->join('tibia_channel', 'tibia_channel.fk_id_tibia', '=', 'tibia.id')
                ->join('teamspeaks', 'teamspeaks.fk_id_bot', '=', 'bots.id')
                ->select('*', 'tibia_api_server.host AS server_api_host')
                ->where('tibia.active', 1)
                ->get();

            foreach ($tibiasv as $sv) {
                $this->tibia = $sv;
                $connect = $this->tsAdmin->connect($sv->host, $sv->queryport, $sv->querylogin, $sv->querypassword);
                $success = $this->tsAdmin->selectInstance($sv->port);

                if (!$connect || !$success) {
                    continue;
                }

                $this->friends();
                $this->hunteds();
                $this->neutrals();
                $this->ally();
                $this->enemy();
                $this->alertConnect();
                $this->alertLevelup();
                $this->deaths();
                $this->claimed();
            }
        } catch (Exception $ex) {
            logger('TibiaBotCase')->error($ex->getMessage());
            return false;
        }
    }

    private function friends()
    {
        if (!$this->tibia) {
            return false;
        }

        $data = [];
        $dataFriends = [];
        $guilds = TibiaFriendList::where('fk_id_tibia', $this->tibia->id)->get();

        foreach ($guilds as $g) {
            $dataFriends[] = $g->tibia_guild;
        }

        $mongo = $this->mongodb->selectCollection('tibiabot', 'guilds');
        $friends = $mongo->find(['guild' => ['$in' => $dataFriends]])->toArray();

        if (!$friends[0]) {
            return false;
        }
        $friends = $this->order($friends, 'vocation', true);
        $friends = $this->order($friends, 'level', true);
        
        $guilds = $this->guilds($friends, $this->tibia->level_tibia, 'friends');

        $online = $guilds['online_high'] + $guilds['online_low'];
        $descHigh = $this->message->custom("Friend List - {$guilds['online_high']}/$online de {$guilds['total']} - Updated: " . date('d/m/Y H:i:s'), 'black');
        $high = $this->message->table($guilds['highlist']);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->friend_high_channel), $guilds['online_high']);
        $data['CHANNEL_DESCRIPTION'] = $descHigh . "\n" . $high;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->friend_high_channel, $data);

        $descLow = $this->message->custom("Friend List - {$guilds['online_low']}/$online de {$guilds['total']} - Updated: " . date('d/m/Y H:i:s'), 'black');
        $low = $this->message->table($guilds['lowlist']);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->friend_low_channel), $guilds['online_low']);
        $data['CHANNEL_DESCRIPTION'] = $descLow . "\n" . $low;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->friend_low_channel, $data);
        return true;
    }

    private function hunteds()
    {
        if (!$this->tibia) {
            return false;
        }

        $data = [];
        $dataHunteds = [];
        $guilds = TibiaHuntedList::where('fk_id_tibia', $this->tibia->id)->get();

        foreach ($guilds as $g) {
            $dataHunteds[] = $g->tibia_guild;
        }

        $mongo = $this->mongodb->selectCollection('tibiabot', 'guilds');
        $hunteds = $mongo->find(['guild' => ['$in' => $dataHunteds]])->toArray();
        if (!$hunteds[0]) {
            return false;
        }

        $hunteds = $this->order($hunteds, 'level', true);
        $hunteds = $this->order($hunteds, 'vocation', true);

        $guilds = $this->guilds($hunteds, $this->tibia->level_tibia, 'hunteds');
        $online = $guilds['online_high'] + $guilds['online_low'];
        $descHigh = $this->message->custom("Hunted List - {$guilds['online_high']}/$online de {$guilds['total']} - Updated: " . date('d/m/Y H:i:s'), 'black');
        $high = $this->message->table($guilds['highlist']);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->hunted_high_channel), $guilds['online_high']);
        $data['CHANNEL_DESCRIPTION'] = $descHigh . "\n" . $high;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->hunted_high_channel, $data);

        $descLow = $this->message->custom("Hunted List - {$guilds['online_low']}/$online de {$guilds['total']} - Updated: " . date('d/m/Y H:i:s'), 'black');
        $low = $this->message->table($guilds['lowlist']);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->hunted_low_channel), $guilds['online_low']);
        $data['CHANNEL_DESCRIPTION'] = $descLow . "\n" . $low;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->hunted_low_channel, $data);
        return true;
    }

    private function neutrals()
    {
        $total = 0;
        $neutral = [];
        $mongo = $this->mongodb->selectCollection('tibiabot', 'neutrals');
        $neutrals = $mongo->findOne(['server' => $this->tibia->server]);

        if (!$neutrals['data']) {
            return false;
        }

        $f = $this->getArray($this->getData('friends'));
        $h = $this->getArray($this->getData('hunteds'));

        $neutrals = $this->order($neutrals, 'level', false);

        foreach ($neutrals['data'] as $player) {
            $this->setData($player, 'neutrals');
            $total++;
            $player['vocation'] = $this->vocation($player['vocation']);
            if (in_array($player['name'], $f) || in_array($player['name'], $h)) {
                continue;
            }

            if ($total < self::LIMITE_DESCRIPTION) {
                $neutral[] = ["{$player['vocation']} →", "[+{$player['level']}] " . $this->message->custom($player['name'], '#BEBEBE')];
            }
        }

        $descNeutral = $this->message->custom("Neutral List - " . self::LIMITE_DESCRIPTION . "/{$total} Onlines - Updated: " . date('d/m/Y H:i:s'), 'black');
        $high = $this->message->table($neutral);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->neutral_channel), $total);
        $data['CHANNEL_DESCRIPTION'] = $descNeutral . "\n" . $high;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->neutral_channel, $data);
    }

    private function ally()
    {
        $total = 0;
        $onlines = 0;
        $allys = [];
        $ally = TibiaAllyList::where('fk_id_tibia', $this->tibia->id)->get();

        if (!$ally) {
            return false;
        }

        foreach ($ally as $player) {
            $this->setData($player, 'ally');
            $add = false;
            foreach ($this->data['neutrals'] as $v) {
                if ($total < self::LIMITE_DESCRIPTION && trim($player->player_ally) == trim($v['name'])) {
                    $allys[] = ["{$v['vocation']} →", "[+{$v['level']}] " . $this->message->success($v['name']), $this->message->success("● Online")];
                    $onlines++;
                    $add = true;
                    break;
                }
            }

            if ($add == false && $total < self::LIMITE_DESCRIPTION) {
                $allys[] = [$this->message->custom($player->player_ally, '#BEBEBE'), $this->message->error("● Offline")];
            }

            $total++;
        }

        $desc = $this->message->custom("Ally List - {$onlines}/{$total} - Updated: " . date('d/m/Y H:i:s'), 'black');
        $al = $this->message->table($allys);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->ally_channel), $onlines);
        $data['CHANNEL_DESCRIPTION'] = $desc . "\n" . $al;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->ally_channel, $data);
    }

    private function enemy()
    {
        $total = 0;
        $onlines = 0;
        $enemys = [];
        $enemy = TibiaEnemyList::where('fk_id_tibia', $this->tibia->id)->get();

        if (!$enemy) {
            return false;
        }

        foreach ($enemy as $player) {
            $add = false;
            $this->setData($player, 'enemy');
            foreach ($this->data['neutrals'] as $v) {
                if ($total < self::LIMITE_DESCRIPTION && trim($player->player_enemy) == trim($v['name'])) {
                    $enemys[] = ["{$v['vocation']} →", "[+{$v['level']}] " . $this->message->error($v['name']), $this->message->success("● Online")];
                    $onlines++;
                    $add = true;
                    break;
                }
            }

            if ($add == false && $total < self::LIMITE_DESCRIPTION) {
                $enemys[] = [$this->message->custom($player->player_enemy, '#BEBEBE'), $this->message->error("● Offline")];
            }

            $total++;
        }

        $desc = $this->message->custom("Enemy List - {$onlines}/{$total} - Updated: " . date('d/m/Y H:i:s'), 'black');
        $al = $this->message->table($enemys);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->enemy_channel), $onlines);
        $data['CHANNEL_DESCRIPTION'] = $desc . "\n" . $al;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->enemy_channel, $data);
    }

    private function claimed()
    {
        $count = 0;
        $claimed = TibiaClaimedPlayer::where('fk_id_tibia', $this->tibia->id)->get();

        if (!$claimed) {
            return null;
        }

        $data = [];
        foreach ($claimed as $val) {
            $city = TibiaClaimedCity::where('cod_city', $val->fk_id_claimed_city)->first();
            if (time() >= strtotime($val->created_at . ' +2 hour')) {
                TibiaClaimedPlayer::find($val->id)->delete();
            } else if ($count < self::LIMITE_DESCRIPTION) {
                $data[] = ["Player: " . $this->message->info($val->player), "City: " . $this->message->success($city->city), "Respawn: " . $this->message->custom($city->respawn, 'PURPLE'), "Finish: " . date('d/m/Y H:i:s', strtotime($val->created_at . '+2 hour'))];
            }
            $count++;
        }

        $desc = $this->message->custom("Claimed - Updated: " . date('d/m/Y H:i:s'), 'black');
        $cl = $this->message->table($data);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->claimed_channel), $count);
        $data['CHANNEL_DESCRIPTION'] = $desc . "\n" . $cl;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->claimed_channel, $data);
    }

    private function deaths()
    {
        $total = 0;
        $death = [];
        $lastDeath = 0;
        $mongo = $this->mongodb->selectCollection('tibiabot', 'deaths');
        $deaths = $mongo->findOne(['server' => $this->tibia->server]);
        if (!$deaths['data']) {
            return false;
        }

        $f = $this->getArray($this->getData('friends'));
        $h = $this->getArray($this->getData('hunteds'));
        $a = $this->getArray($this->getData('ally'));
        $e = $this->getArray($this->getData('enemy'));

        $notify = Notification::find($this->tibia->fk_id_bot);
        if (!$notify) {
            $notify = new Notification();
            $notify->fk_id_bot = $this->tibia->fk_id_bot;
        }

        $lastDeath = [];
        foreach ($deaths['data'] as $player) {
            $msg = null;
            $playerDeath = null;

            if (in_array($player['name'], $f)) {
                $playerDeath = ["[" . date('d/m/Y H:i:s', strtotime($player['hours'])) . "] → ", $this->message->success($player['name'])];
                $msg = $this->message->custom('[DEATH]', '#979797') . " "  . $this->message->success($player['name']) . " ≈ " . $this->message->success("FRIEND") . " → " . $this->message->info($player['reason']);
                $total++;
            } else if (in_array($player['name'], $h)) {
                $playerDeath = ["[" . date('d/m/Y H:i:s', strtotime($player['hours'])) . "] → ", $this->message->error($player['name'])];
                $msg = $this->message->custom('[DEATH]', '#979797') . " "  . $this->message->error($player['name']) . " ≈ " . $this->message->error("HUNTED") . " → " . $this->message->info($player['reason']);
                $total++;
            } else if (in_array($player['name'], $a)) {
                $playerDeath = ["[" . date('d/m/Y H:i:s', strtotime($player['hours'])) . "] → ", $this->message->success($player['name'])];
                $msg = $this->message->custom('[DEATH]', '#979797') . " "  . $this->message->success($player['name']) . " ≈ " . $this->message->success("ALLY") . " → " . $this->message->info($player['reason']);
                $total++;
            } else if (in_array($player['name'], $e)) {
                $playerDeath = ["[" . date('d/m/Y H:i:s', strtotime($player['hours'])) . "] → ", $this->message->error($player['name'])];
                $msg = $this->message->custom('[DEATH]', '#979797') . " " . $this->message->error($player['name']) . " ≈ " . $this->message->error("ENEMY") . " → " . $this->message->info($player['reason']);
                $total++;
            }

            if ($total < self::LIMITE_DESCRIPTION && $playerDeath) {
                $death[] = $playerDeath;
            }

            if ($msg && strtotime($player['hours']) > strtotime($notify->deaths)) {
                $lastDeath[] = $player['hours'];
                $this->news($msg);
                $this->tsAdmin->tsAdmin()->sendMessage(3, 5, $msg);
            }
        }

        logger('deaths')->debug("Notify: ". print_r($notify, true));
        logger('deaths')->debug("Player: ". print_r($player, true));
        logger('deaths')->debug("lastDeath: ". print_r($lastDeath, true));

        if ($lastDeath[0]) {
            $notify->deaths = date('Y-m-d H:i:s', strtotime($lastDeath[0]));
            $notify->save();
        }

        $descDeath = $this->message->custom("Deaths List - {$total}/100 - Updated: " . date('d/m/Y H:i:s'), 'black');
        $high = $this->message->table($death);
        $data['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->death_channel), $total);
        $data['CHANNEL_DESCRIPTION'] = $descDeath . "\n" . $high;
        $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->death_channel, $data);
    }

    private function alertLevelup()
    {
        $mongo = $this->mongodb->selectCollection('tibiabot', 'levelups');
        $levelups = $mongo->findOne(['server' => $this->tibia->server]);
        if (!$levelups['data']) {
            return false;
        }

        $f = $this->getArray($this->getData('friends'));
        $h = $this->getArray($this->getData('hunteds'));
        $a = $this->getArray($this->getData('ally'));
        $e = $this->getArray($this->getData('enemy'));

        foreach ($levelups['data'] as $player) {
            $msg = null;
            if (in_array($player['name'], $f)) {
                $msg = $this->message->info("[LEVELUP] ")  . $this->vocation($player['vocation']) . " → [↑{$player['level']}] " . $this->message->success($player['name']) . " ≈ " . $this->message->success("FRIEND");
            } else if (in_array($player['name'], $h)) {
                $msg = $this->message->info("[LEVELUP] ")  . $this->vocation($player['vocation']) . " → [↑{$player['level']}] " . $this->message->error($player['name']) . " ≈ " . $this->message->error("HUNTED");
            } else if (in_array($player['name'], $a)) {
                $msg = $this->message->info("[LEVELUP] ")  . $this->vocation($player['vocation']) . " → [↑{$player['level']}] " . $this->message->success($player['name']) . " ≈ " . $this->message->success("ALLY");
            } else if (in_array($player['name'], $e)) {
                $msg = $this->message->info("[LEVELUP] ")  . $this->vocation($player['vocation']) . " → [↑{$player['level']}] " . $this->message->error($player['name']) . " ≈ " . $this->message->error("ENEMY");
            }

            if ($msg) {
                $this->news($msg);
                $this->tsAdmin->tsAdmin()->sendMessage(3, 5, $msg);
            }
        }
    }

    private function alertConnect()
    {
        $mongo = $this->mongodb->selectCollection('tibiabot', 'connects');
        $levelups = $mongo->findOne(['server' => $this->tibia->server]);
        if (!$levelups['data']) {
            return false;
        }

        $f = $this->getArray($this->getData('friends'));
        $h = $this->getArray($this->getData('hunteds'));
        $a = $this->getArray($this->getData('ally'));
        $e = $this->getArray($this->getData('enemy'));

        foreach ($levelups['data'] as $player) {
            $msg = null;
            if (in_array($player['name'], $f)) {
                $msg = $this->message->custom("[CONNECTED] ", '#AE00FF')  . $this->vocation($player['vocation']) . " → [+{$player['level']}] " . $this->message->success($player['name']) . " ≈ " . $this->message->success("FRIEND");
            } else if (in_array($player['name'], $h)) {
                $msg = $this->message->custom("[CONNECTED] ", '#AE00FF')  . $this->vocation($player['vocation']) . " → [+{$player['level']}] " . $this->message->error($player['name']) . " ≈ " . $this->message->error("HUNTED");
            } else if (in_array($player['name'], $a)) {
                $msg = $this->message->custom("[CONNECTED] ", '#AE00FF')  . $this->vocation($player['vocation']) . " → [+{$player['level']}] " . $this->message->success($player['name']) . " ≈ " . $this->message->success("ALLY");
            } else if (in_array($player['name'], $e)) {
                $msg = $this->message->custom("[CONNECTED] ", '#AE00FF')  . $this->vocation($player['vocation']) . " → [+{$player['level']}] " . $this->message->error($player['name']) . " ≈ " . $this->message->error("ENEMY");
            }

            if ($msg) {
                $this->news($msg);
                $this->tsAdmin->tsAdmin()->sendMessage(3, 5, $msg);
            }
        }
    }

    private function news($msg)
    {
        $limit = 10;
        $description = $this->tsAdmin->tsAdmin()->channelInfo($this->tibia->news_channel)['data']['channel_description'];
        $c = 1;

        $separetor = $this->message->custom("---------------------------------------------------------------------------------------------------", "#cccccc");
        if ($this->tibia->news_channel) {
            $data = explode('\n', $description);
            $desc = "[" . date('d/m/Y H:i:s', time()) . "] - " . $msg . "\n";
            for ($x = 0; $x < count($data); $x++) {
                if ($data[$x] && $c < $limit && $data[$x] != $separetor) {
                    $desc .= $separetor . "\n";
                    $desc .= $data[$x] . "\n";
                    $c++;
                }
            }
            $dataChannel['CHANNEL_NAME'] = $this->channelOnline($this->tsAdmin->tsAdmin()->channelInfo($this->tibia->news_channel), $c);
            $dataChannel['CHANNEL_DESCRIPTION'] = $desc;
            $this->tsAdmin->tsAdmin()->channelEdit($this->tibia->news_channel, $dataChannel);
        }
    }

    private function guilds($data, $level_tibia, $type = 'data')
    {
        $total = 0;
        $online_high = 0;
        $online_low = 0;
        $highlist = [];
        $lowlist = [];

        if ($data) {
            foreach ($data as $guild) {
                foreach ($guild['data'] as $player) {
                    if (strtolower($player['status']) == 'online') {
                        if ($player['level'] >= $level_tibia) {
                            $player['vocation'] = $this->vocation($player['vocation']);
                            if ($online_high < self::LIMITE_DESCRIPTION) {
                                $highlist[] = ["{$player['vocation']} →", "[+{$player['level']}] " . ($type == 'hunteds' ? $this->message->error($player['name']) : $this->message->success($player['name'])), "≈ " . $guild['guild']];
                            }
                            $online_high++;
                        } else {
                            $player['vocation'] = $this->vocation($player['vocation']);
                            if ($online_low < self::LIMITE_DESCRIPTION) {
                                $lowlist[] = ["{$player['vocation']} →", "[+{$player['level']}] " . ($type == 'hunteds' ? $this->message->error($player['name']) : $this->message->success($player['name'])), "≈ " . $guild['guild']];
                            }
                            $online_low++;
                        }
                        $this->setData($player, $type);
                    }
                    $total++;
                }
            }
            return ['highlist' => $highlist, 'lowlist' => $lowlist, 'online_high' => $online_high, 'online_low' => $online_low, 'total' => $total];
        }
        return false;
    }

    private function setData($data, $indice)
    {
        $this->data[$indice][] = $data;
    }

    private function getData($indice)
    {
        return $this->data[$indice];
    }

    private function cleanData()
    {
        unset($this->data);
    }

    private function getArray($data)
    {
        $players = [];
        foreach ($data as $ret) {
            $players[] = $ret->name;
        }
        return $players;
    }
    /**
     * Reduz o vocation do personagem para 2 letras iniciais de cada classe.
     * @param string $vocation
     * @return string
     */
    private function vocation($vocation)
    {
        if (strpos($vocation, " ") !== false) {
            $str = explode(" ", $vocation);
            return strtoupper(substr($str[0], 0, 1) . substr($str[1], 0, 1));
        } else {
            return strtoupper(substr($vocation, 0, 2));
        }
    }

    /**
     * Edita o name da sala com a quantidade de onlines.
     * @param string $channel
     * @param int $online
     */
    private function channelOnline($data, $online)
    {
        $array = explode("(", $data['data']['channel_name']);
        return trim($array[0]) . " ($online)";
    }

    private function order($data, $indice, $mult, $op = '<')
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
}
