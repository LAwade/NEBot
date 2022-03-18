<?php

namespace App\Providers;

use App\Interfaces\ICommand;
use App\Consumers\CommandsTibia;
use App\Models\Bot;
use App\Models\TibiaClaimedCity;

class CommandTSProvider extends CommandsTibia implements ICommand
{

    /** LIST COMMANDS */
    public function help($tsAdmin, $message, $data = null)
    {
        $comands = configure('commands')['commands'];
        $translator = configure('commands')['translator'];
        $lang = 'en';
        $display = "\n" . $message->separetor();
        foreach ($comands as $key => $cmd) {
            $display .= $message->info($data['bot']['tag'] . $key) .  $message->custom(" [$cmd]", '#DEDEDE') . " → " . $message->alert(sprintf($translator[$lang][$key], $data['bot']['tag'])) . "\n";
            $display .= $message->separetor();
        }
        $tsAdmin->tsAdmin()->sendMessage(3, 5, $display);
    }

    /** MASS POKE */
    public function mp($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice");
        $msg = trim(str_replace($data['commands'][0], '', $data['msg']));
        foreach ($clientes['data'] as $online) {
            $tsAdmin->tsAdmin()->clientPoke($online['clid'], $message->error("{$data['invoker']['name']}: ") . $message->success($msg));
        }
    }

    /** MASS MOVE */
    public function mm($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice");
        $channel = $tsAdmin->tsAdmin()->channelFind($data['commands'][1]);

        if (!$channel['data'][0]['cid']) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("A sala não foi encontrada!"));
        }

        foreach ($clientes['data'] as $online) {
            if ($online['cid'] >= 1) {
                $tsAdmin->tsAdmin()->clientMove($online['clid'], $channel['data'][0]['cid']);
            }
        }
    }

    /** MASS MOVE ONLY USER OFFLINE */
    public function mmoff($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice");
        $channel = $tsAdmin->tsAdmin()->channelFind($data['commands'][1]);

        if (!$channel['data'][0]['cid']) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("A sala não foi encontrada!"));
        }

        foreach ($clientes['data'] as $online) {
            if ($online['cid'] >= 1 && ($online['client_input_muted'] || $online['client_output_muted'] || $online['client_away'])) {
                $tsAdmin->tsAdmin()->clientMove($online['clid'], $channel['data'][0]['cid']);
            }
        }
    }

    /** MASS KICK */
    public function mk($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice");
        $msg = trim(str_replace($data['commands'][0], '', $data['msg']));
        foreach ($clientes['data'] as $online) {
            if ($online['cid'] >= 1) {
                $tsAdmin->tsAdmin()->clientKick($online['clid'], "server", $msg);
            }
        }
    }

    /** MASS KICK ONLY USER OFFLINE */
    public function mkoff($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice");
        $msg = trim(str_replace($data['command'][0], '', $data['msg']));
        foreach ($clientes['data'] as $online) {
            if ($online['cid'] >= 1 && ($online['client_input_muted'] || $online['client_output_muted'] || $online['client_away'])) {
                $tsAdmin->tsAdmin()->clientKick($online['clid'], "server", $message->success($msg));
            }
        }
    }

    /** MASS KICK CHANNEL */
    public function mck($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice");
        $msg = trim(str_replace($data['command'][0], '', $data['msg']));
        foreach ($clientes['data'] as $online) {
            if ($online['cid'] >= 1) {
                $tsAdmin->tsAdmin()->clientKick($online['clid'], "channel", $message->info($msg));
            }
        }
    }

    /** MOVE GROUP */
    public function mg($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice -groups");
        $groups = $tsAdmin->tsAdmin()->serverGroupList();

        $response = false;
        $groupId = null;

        foreach ($groups['data'] as $group) {
            if ($group['name'] == $data['commands'][2]) {
                $response = true;
                $groupId = $group['sgid'];
                break;
            }
        }

        $channel = $tsAdmin->tsAdmin()->channelFind($data['commands'][1]);
        if (!$channel['data'][0]['cid']) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("A sala não foi encontrada!"));
            return null;
        }

        if ($response) {
            foreach ($clientes['data'] as $online) {
                $groupsClient = explode(',', $online['client_servergroups']);
                if (in_array($groupId, $groupsClient)) {
                    $tsAdmin->tsAdmin()->clientMove($online['clid'], $channel['data'][0]['cid']);
                }
            }
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success("Comando executado!"));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("O Grupo não foi encontrado!"));
        }
    }

    /** MOVE TRACK GROUP */
    public function mtg($tsAdmin, $message, $data)
    {
        $clientes = $tsAdmin->tsAdmin()->clientList("-away -voice -groups");
        $groups = $tsAdmin->tsAdmin()->serverGroupList();

        $response = false;
        $groupId = null;

        foreach ($groups['data'] as $group) {
            if ($group['name'] == $data['commands'][1]) {
                $response = true;
                $groupId = $group['sgid'];
                break;
            }
        }

        if ($response) {
            $client = $tsAdmin->tsAdmin()->clientInfo($data['invoker']['id']);
            foreach ($clientes['data'] as $online) {
                $groupsClient = explode(',', $online['client_servergroups']);
                if (in_array($groupId, $groupsClient)) {
                    $tsAdmin->tsAdmin()->clientMove($online['clid'], $client['data']['cid']);
                }
            }
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success("Comando executado!"));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("O Grupo não foi encontrado!"));
        }
    }

    public function rps($tsAdmin, $message, $data = null)
    {
        $clients = $tsAdmin->tsAdmin()->clientList();
        $permissions = array();
        $permissions['permissionName'] = 'i_needed_modify_power_client_use_priority_speaker';
        $permissions['permissionName'] = 'i_needed_modify_power_client_is_priority_speaker';
        $permissions['permissionName'] = 'b_client_use_priority_speaker';
        $permissions['permissionName'] = 'b_client_is_priority_speaker';

        foreach ($clients['data'] as $value) {
            $tsAdmin->tsAdmin()->clientDelPerm($value['client_database_id'], $permissions);
        }

        $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success("Priority Speaker removidos!"));
    }

    /** CREATE GRUOP ADMIN */
    public function cgadmin($tsAdmin, $message, $data)
    {

        $isSA = $tsAdmin->isServerAdmin($data['invoker']['id']);
        if (!$isSA['success']) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error(sprintf("Permissões de cliente insuficientes! (Verificar em %s)", implode(', ', $isSA['permissions']))));
            return;
        }

        $groups = array('sgid_bot' => '[ADMIN.BOT]', 'sgid_claimed' => '[CLAIMED.BOT]');
        $grpsuccess = array();

        $permissions = array();
        $permissions["b_group_is_permanent"] = array(1, 0, 0);
        $permissions["b_client_server_textmessage_send"] = array(1, 0, 0);
        $permissions["i_group_sort_id"] = array(1, 0, 0);

        $exgrp = $tsAdmin->tsAdmin()->serverGroupList();
        foreach ($exgrp['data'] as $v) {
            if (in_array($v['name'], $groups)) {
                $tsAdmin->tsAdmin()->serverGroupDelete($v['sgid'], 1);
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->alert("O grupo {$v['name']} foi removido!"));
            }
        }

        foreach ($groups as $k => $val) {
            $grp = $tsAdmin->tsAdmin()->serverGroupAdd($val, 1);
            $gdata = $tsAdmin->tsAdmin()->serverGroupAddPerm($grp['data']['sgid'], $permissions);
            if ($gdata['errors']) {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Não foi possível criar o Grupo {$val}, por favor crie manualmente adicionando a permissão de escrita na sala do servidor!"));
            } else {
                $grpadd = $tsAdmin->tsAdmin()->serverGroupList();
                foreach ($grpadd['data'] as $cg) {
                    if ($cg['name'] == $val) {
                        $grpsuccess[$k] = $cg['sgid'];
                    }
                }
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success("O grupo {$val} foi criado com sucesso!"));
            }
        }
        if ($grpsuccess) {
            $bot = Bot::find($data['bot']['id']);
            $bot->sgid_bot = $grpsuccess['sgid_bot'];
            $bot->sgid_claimed = $grpsuccess['sgid_claimed'];
            $bot->save();
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success("Grupos foram cadastrados com sucesso!"));
        }
    }

    ###############################################################
    ####                CONFIGURAÇÃO DE TIBIA                  ####
    ###############################################################

    public function rashid($tsAdmin, $message, $data)
    {
        $reponse = $this->findrashid();
        $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->custom("Rashid está em {$reponse['data']} [Timezone CEST]\n", '#8946FF'));
    }

    public function servertibia($tsAdmin, $message, $data)
    {
        $result = $this->listserverstibia();
        if ($result['success']) {
            $servidores = "\n";
            foreach ($result['data'] as $tibia) {
                $servidores .= $message->custom("ID: {$tibia->id} → {$tibia->server} \n", '#8946FF');
            }
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $servidores);
        }
    }

    public function conftibia($tsAdmin, $message, $data)
    {
        if ($data['bot']['id'] && $data['commands'][1]) {
            $world = isset($data['commands'][2]) ? $data['commands'][2] : null;
            $response = $this->configTibia($data['bot']['id'], $data['commands'][1], $world);
        } else {
            return $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("É necessário informar o código do servidor!"));
        }

        if ($response['success']) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message']));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
        }
    }

    public function channelslist($tsAdmin, $message, $data)
    {
        $channels = [];
        $channelstibia = configure('channelstibia');
        $bot = Bot::find($data['bot']['id']);

        foreach ($channelstibia['config_channels'] as $channel) {
            $find_channel = $tsAdmin->tsAdmin()->channelFind(sprintf($channel['channel'], $bot->level_tibia));

            if (isset($find_channel['data'][0]['cid'])) {
                $tsAdmin->tsAdmin()->channelDelete($find_channel['data'][0]['cid'], 1);
            }

            $configs = [];
            $configs['CHANNEL_NAME'] = sprintf($channel['channel'], $bot->level_tibia);
            $configs['CHANNEL_FLAG_PERMANENT'] = 1;
            $cid = $tsAdmin->tsAdmin()->channelCreate($configs);

            $errors = [];
            if ($cid['errors']) {
                $errors[] = $cid['errors'][0];
            }

            $permissions = array();
            $permissions['i_channel_needed_delete_power'] = 0;
            $permissions['i_channel_needed_modify_power'] = 0;
            $permissions['i_channel_needed_permission_modify_power'] = 0;
            $tsAdmin->tsAdmin()->channelAddPerm($cid['data']['cid'], $permissions);
            $channels[$channel['table']] = $cid['data']['cid'];
        }

        $response = $this->tibiachannels($data['bot']['id'], $channels, $data['commands'][1]);
        if ($response['success'] && !$errors) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message']));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message'] . " Errors[" . count($errors) . "]: " . $errors[0]));
        }
    }

    public function addfriend($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->addNewFriend($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['guild'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de uma guild para cadastrar!"));
        }
    }

    public function friends($tsAdmin, $message, $data)
    {
        $response = $this->listfriend($data['bot']['id']);
        if ($response['data']) {
            $friends = "\n";
            foreach ($response['data'] as $key => $value) {
                $friends .= "ID: {$key}  →  {$value} \n";
            }
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->custom($friends, '#8946FF'));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Não foram encontrados nenhum friend!"));
        }
    }

    public function rmfriend($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->removeFriend($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['guild'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de uma guild para remover!"));
        }
    }

    public function addhunted($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->addNewHunted($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['guild'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de uma guild para cadastrar!"));
        }
    }

    public function hunteds($tsAdmin, $message, $data)
    {
        $response = $this->listHunted($data['bot']['id']);
        if ($response['data']) {
            $hunteds = "\n";
            foreach ($response['data'] as $key => $value) {
                $hunteds .= "ID: {$key}  →  {$value} \n";
            }
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->custom($hunteds, '#8946FF'));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Não foram encontrados nenhum hunted!"));
        }
    }

    public function rmhunted($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->removeHunted($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['guild'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de uma guild para remover!"));
        }
    }

    public function addally($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->addNewAlly($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['player'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de um player para cadastrar!"));
        }
    }

    public function rmally($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->removeAlly($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['player'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de uma guild para remover!"));
        }
    }

    public function addenemy($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->addNewEnemy($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['player'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de um player para cadastrar!"));
        }
    }

    public function rmenemy($tsAdmin, $message, $data)
    {
        if ($data['commands'][1]) {
            $response = $this->removeEnemy($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])));
            if ($response['success']) {
                foreach ($response['data'] as $guild) {
                    $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($response['message'] . " " . trim($guild['player'])));
                }
            } else {
                $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($response['message']));
            }
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error("Informe nome de uma guild para remover!"));
        }
    }

    public function claimed($tsAdmin, $message, $data)
    {
        $client = $tsAdmin->tsAdmin()->clientInfo($data['bot']['id']);

        if ($data['commands'][1] && !$data['commands'][2]) {
            $seachCity = TibiaClaimedCity::where('cod_city', '>=', $data['commands'][1])->where('cod_city', '<=', ($data['commands'][1] + 100))->get();
            $city = "\n";
            foreach ($seachCity as $cty) {
                $city .= "Cod. City: [ " . $message->info($cty->cod_city) . " ] City: [ " . $message->info($cty->city) . " ] Respawn: [ " . $message->info($cty->respawn) . " ]\n";
            }
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $city);
            return;
        }

        $name = trim(str_replace(end($data['commands']), '', str_replace($data['commands'][0], '', $data['msg'])));
        $clameid = $this->confclaimed($data['bot']['id'], trim(end($data['commands'])), $name, $client['data']['client_database_id']);

        if ($clameid['success']) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($clameid['message']));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($clameid['message']));
        }
    }

    public function rmclaimed($tsAdmin, $message, $data)
    {
        $client = $tsAdmin->tsAdmin()->clientInfo($data['bot']['id']);

        $clameid = $this->removeClaimed($data['bot']['id'], trim(str_replace($data['commands'][0], '', $data['msg'])), false, $client['data']['client_database_id']);
        if ($clameid['success']) {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->success($clameid['message']));
        } else {
            $tsAdmin->tsAdmin()->sendMessage(3, 5, $message->error($clameid['message']));
        }
    }
}
