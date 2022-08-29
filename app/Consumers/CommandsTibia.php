<?php

namespace App\Consumers;

use App\Models\Bot;
use App\Models\Tibia;
use App\Models\TibiaAllyList;
use App\Models\TibiaApi;
use App\Models\TibiaChannel;
use App\Models\TibiaClaimedCity;
use App\Models\TibiaClaimedPlayer;
use App\Models\TibiaEnemyList;
use App\Models\TibiaFriendList;
use App\Models\TibiaHuntedList;
use Exception;

class CommandsTibia
{

    public function configTibia($idbot, $server, $world = null)
    {
        try {
            $apiserver = TibiaApi::find($server);
            if (!$apiserver) {
                return $this->getData(false, 'O name informado do Servidor de Tibia é inválido!');
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            if (!$tibia) {
                $tibia = new Tibia;
            }
            $tibia->world = $world ?? null;
            $tibia->fk_id_bot = $idbot;
            $tibia->fk_id_tibia_api = $server;
            if ($tibia->save()) {
                return $this->getData(true, 'Suas configurações foram salvas!');
            } else {
                return $this->getData(false, 'Não foi possível registrar as configurações!');
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível registrar as configurações!');
        }
    }

    public function listserverstibia()
    {
        try {
            $tibiaapi = TibiaApi::all();
            if ($tibiaapi) {
                return $this->getData(true, 'Servidores encontrados!', $tibiaapi);
            }
            return $this->getData(false, 'Nenhum servidor de Tibia encontrado!');
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível listar os servidores!');
        }
    }

    public function confclaimed($idbot, $codecity, $player = null, $dbid = null)
    {
        try {
            $bot = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $bot->id;

            if (!$idtibia && !$codecity && !$player) {
                return $this->getData(false, 'É necessário informar todos os parametros de entrada!');
            }

            $channels = TibiaChannel::where('fk_id_tibia', $idtibia)->first();

            if (!$channels->claimed_channel) {
                return $this->getData(false, 'A sala de Claimed não foi configurada!');
            }

            $codeCity = TibiaClaimedCity::where('cod_city', $codecity)->first();
            if (!$codeCity) {
                return $this->getData(false, 'Código da cidade informado não foi encontrado!');
            }

            $cmddbid = TibiaClaimedPlayer::where('cldbid', $dbid)->orWhere('player', $player)->count();
            if ($cmddbid) {
                return $this->getData(false, 'Você já cadastrou um respawn!');
            }

            $fkcity = TibiaClaimedPlayer::where('fk_id_claimed_city', $codecity)->count();
            if ($fkcity) {
                return $this->getData(false, 'O respawn na cidade informada já está em uso!');
            }

            $claimed = new TibiaClaimedPlayer();
            $claimed->player = $player;
            $claimed->fk_id_tibia = $idtibia;
            $claimed->fk_id_claimed_city = $codecity;
            $claimed->cldbid = $dbid;

            if ($claimed->save()) {
                return $this->getData(true, 'O respawn foi criado, aguarde para atualizar a lista de claimed!');
            }
            return $this->getData(false, 'O respawn na cidade informada já está em uso!');
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível adicionar o claimed!' . $e->getMessage());
        }
    }

    public function removeClaimed($idbot, $player, $isAdmin = false, $dbid = null)
    {
        try {
            $bot = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $bot->id;
            if ($isAdmin) {
                $claimedplayer = TibiaClaimedPlayer::where(['fk_id_tibia' => $idtibia, 'player' => $player])->first();
            } else {
                if (!$dbid) {
                    return $this->getData(false, 'Não foi possível remover o claimed pois é necessário informar o código do commander!');
                }
                $claimedplayer = TibiaClaimedPlayer::where(['fk_id_tibia' => $idtibia, 'player' => $player, 'cldbid' => $dbid])->first();
            }

            if ($claimedplayer && $claimedplayer->delete()) {
                return $this->getData(true, 'Player foi removido da lista de claimed!');
            }

            return $this->getData(false, 'Player não encontrado na lista de claimed!');
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível remover o claimed!');
        }
    }

    public function tibiachannels($idbot, array $channels, $level = null)
    {
        try {
            $bot = Tibia::where('fk_id_bot', $idbot)->first();

            if (!$bot) {
                return $this->getData(false, 'Não foi possível encontrar a configuração do BOT! Por favor, use o comando "!conftibia".');
            }

            $chn = TibiaChannel::where('fk_id_tibia', $bot->id)->first();
            if (!$chn) {
                $chn = new TibiaChannel;
            }

            $chn->friend_high_channel = $channels['friend_high_channel'] ?? 0;
            $chn->friend_low_channel = $channels['friend_low_channel'] ?? 0;
            $chn->hunted_high_channel = $channels['hunted_high_channel'] ?? 0;
            $chn->hunted_low_channel = $channels['hunted_low_channel'] ?? 0;
            $chn->neutral_channel = $channels['neutral_channel'] ?? 0;
            $chn->ally_channel = $channels['ally_channel'] ?? 0;
            $chn->enemy_channel = $channels['enemy_channel'] ?? 0;
            $chn->death_channel = $channels['death_channel'] ?? 0;
            $chn->claimed_channel = $channels['claimed_channel'] ?? 0;
            $chn->news_channel = $channels['news_channel'] ?? 0;
            $chn->fk_id_tibia = $bot->id;

            if ($chn->save()) {
                return $this->getData(true, 'Os canais de Tibia foram salvos!');
            }

            return $this->getData(false, 'Não foi possível salvar os canais de Tibia!');
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível configurar as channels!');
        }
    }

    public function addNewFriend($idbot, $friends)
    {
        try {
            $bot = Bot::find($idbot);

            if (!$bot) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;
            $countFriends = TibiaFriendList::where('fk_id_tibia', $idtibia)->count();

            if ($countFriends >= $bot->limit_friend) {
                return $this->getData(false, 'O limite de Friends foi excedido!');
            }

            $addGuildFriend = function ($idtibia, $value) {
                $guild = TibiaFriendList::where('tibia_guild', trim($value))
                    ->where('fk_id_tibia', $idtibia)
                    ->first();

                if (!$guild) {
                    $guild = new TibiaFriendList;
                    $guild->tibia_guild = trim($value);
                    $guild->fk_id_tibia = $idtibia;
                    if ($guild->save()) {
                        return $this->getData(true, "A Guild Friend [{$value}] foi adicionada na lista!");
                    }
                }
                return $this->getData(false, "A Guild Friend [{$value}] não foi possível adicionar a lista!");
            };

            if (strpos($friends, ',') !== false) {
                $guilds = explode(',', $friends);
                $addGuild = [];
                foreach ($guilds as $value) {
                    $resp = $addGuildFriend($idtibia, $value);
                    if ($resp['success']) {
                        $addGuild[] = ['success' => true, 'message' => $resp['message'], "guild" => $value];
                    } else {
                        $addGuild[] = ['success' => false, 'message' => $resp['message'], "guild" => $value];
                    }
                }
                return $this->getData(true, "add guilds friends", $addGuild);
            } else {
                $resp = $addGuildFriend($idtibia, $friends);
                if ($resp['success']) {
                    return $this->getData(true, "A Guild Friend [{$friends}] foi adicionada na lista!", [['success' => false, 'message' => $resp['message'], "guild" => $friends]]);
                } else {
                    return $this->getData(false, "A Guild Friend [{$friends}] não foi possível adicionar a lista!");
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível registrar a guild Friend!');
        }
    }

    public function removeFriend($idbot, $friends)
    {
        try {
            if (!Bot::find($idbot)) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;

            $rmGuildFriend = function ($idtibia, $value) {
                $guild = TibiaFriendList::where('tibia_guild', trim($value))->where('fk_id_tibia', $idtibia)->first();
                if ($guild && $guild->delete()) {
                    return $this->getData(true, "A Guild Friend [{$value}] foi removida na lista!");
                } else {
                    return $this->getData(false, "A Guild Friend [{$value}] não foi possível remover a lista!");
                }
            };

            if (strpos($friends, ',') !== false) {
                $guilds = explode(',', $friends);
                $rmGuild = [];
                foreach ($guilds as $value) {
                    $resp = $rmGuildFriend($idtibia, $value);
                    if ($resp['success']) {
                        $rmGuild[] = ['success' => true, 'message' => $resp['message'], "guild" => $value];
                    } else {
                        $rmGuild[] = ['success' => false, 'message' => $resp['message'], "guild" => $value];
                    }
                }
                return $this->getData(true, "remove guilds friends", $rmGuild);
            } else {
                $resp = $rmGuildFriend($idtibia, $friends);
                if ($resp['success']) {
                    return $this->getData(true, "A Guild Friend [{$friends}] foi removida na lista!", [['success' => true, 'message' => $resp['message'], "guild" => $friends]]);
                } else {
                    return $this->getData(false, "A Guild Friend [{$friends}] não foi possível remover a lista!");
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível remover a guild Friend!');
        }
    }

    public function listFriend($idbot)
    {
        try {
            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;
            $friends = TibiaFriendList::where('fk_id_tibia', $idtibia)->get();
            if ($friends) {
                $data = [];
                foreach ($friends as $value) {
                    $data[$value->id] = $value->tibia_guild;
                }
                return $this->getData(true, "guild finded", $data);
            } else {
                return $this->getData(true, "Não encontramos nenhuma guild friend cadastrada!");
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível listar as guilds Friend!');
        }
    }

    public function addNewHunted($idbot, $hunteds)
    {
        try {
            $bot = Bot::find($idbot);

            if (!$bot) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;
            $countHunteds = TibiaHuntedList::where('fk_id_tibia', $idtibia)->count();

            if ($countHunteds >= $bot->limit_hunted) {
                return $this->getData(false, 'O limite de Hunted foi excedido!');
            }

            $addGuildHunted = function ($idtibia, $value) {
                $guild = TibiaHuntedList::where('tibia_guild', trim($value))->where('fk_id_tibia', $idtibia)->count();
                if (!$guild) {
                    $guild = new TibiaHuntedList();
                    $guild->tibia_guild = trim($value);
                    $guild->fk_id_tibia = $idtibia;
                    if ($guild->save()) {
                        return $this->getData(true, "A Guild Hunted [{$value}] foi adicionada na lista!");
                    }
                }
                return $this->getData(false, "A Guild Hunted [{$value}] não foi possível adicionar a lista!");
            };

            if (strpos($hunteds, ',') !== false) {
                $guilds = explode(',', $hunteds);
                $addGuild = [];
                foreach ($guilds as $value) {
                    $resp = $addGuildHunted($idtibia, $value);
                    if ($resp['success']) {
                        $addGuild[] = ['success' => true, 'message' => $resp['message'], "guild" => $value];
                    } else {
                        $addGuild[] = ['success' => false, 'message' => $resp['message'], "guild" => $value];
                    }
                }
                return $this->getData(true, "add guilds hunted", $addGuild);
            } else {
                $resp = $addGuildHunted($tibia->id, $hunteds);
                if ($resp['success']) {
                    return $this->getData(true, "A Guild Hunted [{$hunteds}] foi adicionada na lista!", [['success' => true, 'message' => $resp['message'], "guild" => $hunteds]]);
                } else {
                    return $this->getData(false, "A Guild Hunted [{$hunteds}] não foi possível adicionar a lista!");
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível adicionar a guild Hunted!');
        }
    }

    public function removeHunted($idbot, $hunteds)
    {
        try {
            if (!Bot::find($idbot)) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();

            $rmGuildHunted = function ($idtibia, $value) {
                $guild = TibiaHuntedList::where([
                    'tibia_guild' => $value,
                    'fk_id_tibia' => $idtibia
                ])->first();

                if ($guild && $guild->delete()) {
                    return $this->getData(true, "A Guild Hunted [{$value}] foi removida na lista!");
                } else {
                    return $this->getData(false, "A Guild Hunted [{$value}] não foi possível remover a lista!");
                }
            };

            if (strpos($hunteds, ',') !== false) {
                $guilds = explode(',', $hunteds);
                $rmGuild = [];
                foreach ($guilds as $value) {
                    $resp = $rmGuildHunted($tibia->id, $value);
                    if ($resp['success']) {
                        $rmGuild[] = ['success' => true, 'message' => $resp['message'], "guild" => $value];
                    } else {
                        $rmGuild[] = ['success' => false, 'message' => $resp['message'], "guild" => $value];
                    }
                }
                return $this->getData(true, "remove guilds hunteds", $rmGuild);
            } else {
                $resp = $rmGuildHunted($tibia->id, $hunteds);
                if ($resp['success']) {
                    return $this->getData(true, $resp['message'], [['success' => true, 'message' => $resp['message'], "guild" => $hunteds]]);
                } else {
                    return $this->getData(false, $resp['message']);
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível remover a guild Hunted!');
        }
    }

    public function listHunted($idbot)
    {
        try {
            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;
            $hunteds = TibiaHuntedList::where('fk_id_tibia', $idtibia)->get();

            if ($hunteds) {
                $data = [];
                foreach ($hunteds as $value) {
                    $data[$value->id] = $value->tibia_guild;
                }
                return $this->getData(true, "guild finded", $data);
            } else {
                return $this->getData(true, "Não encontramos nenhuma guild hunted cadastrada!");
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível listar as guilds Hunted!');
        }
    }

    public function addNewAlly($idbot, $allys)
    {
        try {
            $bot = Bot::find($idbot);

            if (!$bot) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;
            $countAllys = TibiaAllyList::where('fk_id_tibia', $idtibia)->count();

            if ($countAllys >= $bot->limit_ally) {
                return $this->getData(false, 'O limite de Ally foi excedido!');
            }

            $addPlayerAlly = function ($idtibia, $value) {
                $player = TibiaAllyList::where('player_ally', trim($value))->where('fk_id_tibia', $idtibia)->count();
                if (!$player) {
                    $player = new TibiaAllyList;
                    $player->player_ally = trim($value);
                    $player->fk_id_tibia = $idtibia;
                    if ($player->save()) {
                        return $this->getData(true, "O Player Ally [{$value}] foi adicionado na lista!");
                    }
                }
                return $this->getData(false, "O Player Ally [{$value}] não foi possível adicionar a lista!");
            };

            if (strpos($allys, ',') !== false) {
                $players = explode(',', $allys);
                $addPlayer = [];
                foreach ($players as $value) {
                    $resp = $addPlayerAlly($idtibia, $value);
                    if ($resp['success']) {
                        $addPlayer[] = ['success' => true, 'message' => $resp['message'], "player" => $value];
                    } else {
                        $addPlayer[] = ['success' => false, 'message' => $resp['message'], "player" => $value];
                    }
                }
                return $this->getData(true, "add ally", $addPlayer);
            } else {
                $resp = $addPlayerAlly($idtibia, $allys);
                if ($resp['success']) {
                    return $this->getData(true, $resp['message'], [['success' => false, $resp['message'], "player" => $allys]]);
                } else {
                    return $this->getData(false, $resp['message']);
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível adicionar o player Ally!');
        }
    }

    public function removeAlly($idbot, $allys)
    {
        try {
            $bot = Bot::find($idbot);

            if (!$bot) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;

            $rmPlayerAlly = function ($idtibia, $value) {
                $player = TibiaAllyList::where('player_ally', trim($value))->where('fk_id_tibia', $idtibia)->first();
                if ($player && $player->delete()) {
                    return $this->getData(true, "O Player Ally [{$value}] foi removido na lista!");
                } else {
                    return $this->getData(false, "O Player Ally [{$value}] não foi possível remover a lista!");
                }
            };

            if (strpos($allys, ',') !== false) {
                $players = explode(',', $allys);
                $addPlayer = [];
                foreach ($players as $value) {
                    $resp = $rmPlayerAlly($idtibia, $value);
                    if ($resp['success']) {
                        $addPlayer[] = ['success' => true, 'message' => $resp['message'], "player" => $value];
                    } else {
                        $addPlayer[] = ['success' => false, 'message' => $resp['message'], "player" => $value];
                    }
                }
                return $this->getData(true, "remove ally", $addPlayer);
            } else {
                $resp = $rmPlayerAlly($idtibia, $allys);
                if ($resp['success']) {
                    return $this->getData(true, $resp['message'], [['success' => true, 'message' => $resp['message'], "player" => $allys]]);
                } else {
                    return $this->getData(false, $resp['message']);
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível remover o player Ally!');
        }
    }

    public function addNewEnemy($idbot, $enemys)
    {
        try {
            $bot = Bot::find($idbot);
            if (!$bot) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;
            $countEnemy = TibiaEnemyList::where('fk_id_tibia', $idtibia)->count();
            if ($countEnemy >= $bot->limit_enemy) {
                return $this->getData(false, 'O limite de Enemy foi excedido!');
            }

            $addPlayerEnemy = function ($idtibia, $value) {
                $player = TibiaEnemyList::where('player_enemy', trim($value))->where('fk_id_tibia', $idtibia)->count();
                if (!$player) {
                    $player = new TibiaEnemyList;
                    $player->player_enemy = trim($value);
                    $player->fk_id_tibia = $idtibia;
                    if ($player->save()) {
                        return $this->getData(true, "O Player Enemy [{$value}] foi adicionado na lista!");
                    }
                }
                return $this->getData(false, "O Player Enemy [{$value}] não foi possível adicionar a lista!");
            };

            if (strpos($enemys, ',') !== false) {
                $players = explode(',', $enemys);
                $addGuild = [];
                foreach ($players as $value) {
                    $resp = $addPlayerEnemy($idtibia, $value);
                    if ($resp['success']) {
                        $addGuild[] = ['success' => true, 'message' => $resp['message'], "player" => $value];
                    } else {
                        $addGuild[] = ['success' => false, 'message' => $resp['message'], "player" => $value];
                    }
                }
                return $this->getData(true, "add enemy", $addGuild);
            } else {
                $resp = $addPlayerEnemy($idtibia, $enemys);
                if ($resp['success']) {
                    return $this->getData(true, $resp['message'], [['success' => true, 'message' => $resp['message'], "player" => $enemys]]);
                } else {
                    return $this->getData(false, $resp['message']);
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível adicionar o player Enemy!');
        }
    }

    public function removeEnemy($idbot, $enemys)
    {
        try {
            $bot = Bot::find($idbot);
            if (!$bot) {
                return $this->getData(false, "Não foi possível encontrar a configuração do BOT!");
            }

            $tibia = Tibia::where('fk_id_bot', $idbot)->first();
            $idtibia = $tibia->id;
            $rmPlayerEnemy = function ($idtibia, $value) {
                $player = TibiaEnemyList::where('player_enemy', trim($value))->where('fk_id_tibia', $idtibia)->first();
                if ($player && $player->delete()) {
                    return $this->getData(true, "O Player Enemy [{$value}] foi removido na lista!");
                } else {
                    return $this->getData(false, "O Player Enemy [{$value}] não foi possível remover a lista!");
                }
            };

            if (strpos($enemys, ',') !== false) {
                $players = explode(',', $enemys);
                $rmPlayer = [];
                foreach ($players as $value) {
                    $resp = $rmPlayerEnemy($idtibia, $value);
                    if ($resp['success']) {
                        $rmPlayer[] = ['success' => true, 'message' => $resp['message'], "player" => $value];
                    } else {
                        $rmPlayer[] = ['success' => false, 'message' => $resp['message'], "player" => $value];
                    }
                }
                return $this->getData(true, "remove enemy", $rmPlayer);
            } else {
                $resp = $rmPlayerEnemy($idtibia, $enemys);
                if ($resp['success']) {
                    return $this->getData(true, $resp['message'], [['success' => true, 'message' => $resp['message'], "player" => $enemys]]);
                } else {
                    return $this->getData(false, $resp['message']);
                }
            }
        } catch (Exception $e) {
            return $this->getData(false, 'Não foi possível remover o player Enemy!');
        }
    }

    public function findrashid()
    {
        $hashid = array(
            array('Carlin', 'gjx2rJu.gif', 'Domingo'),
            array('Svargrond', 'WS6G4cr.gif', 'Segunda-feira'),
            array('Liberty Bay', 'oQAutOK.gif', 'Terça-feira'),
            array('Port Hope', 'B7wJqp7.gif', 'Quarta-feira'),
            array('Ankrahmun', 'hAsQ5QY.gif', 'Quinta-feira'),
            array('Darashia', '0VeqxNJ.gif', 'Sexta-feira'),
            array('Edron', 'V8nbphh.gif', 'Sábado')
        );

        $dia = date('w', time());
        return $this->getData(true, "Rashid encontrado", "[url=https://i.imgur.com/{$hashid[$dia][1]}]{$hashid[$dia][0]}[/url]");
    }

    private function getData($status, $message, $data = null)
    {
        $response = ['success' => $status, 'message' => $message];
        if ($data) {
            $response['data'] = $data;
        }
        return $response;
    }
}
