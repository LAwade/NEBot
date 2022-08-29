<?php

namespace App\Providers;

use App\Interfaces\ITSAdmin;
use App\Shared\TS3Admin;
use Exception;

class TSAdminProvider implements ITSAdmin
{

    /**
     * @var TS3Admin $tsAdmin
     */
    private $tsAdmin;

    public function tsAdmin($tsAdmin = null): ?TS3Admin
    {
        if ($this->tsAdmin && !$tsAdmin) {
            return $this->tsAdmin;
        } else if ($tsAdmin) {
            $this->tsAdmin = $tsAdmin;
        }
        return null;
    }

    /** CONNECT */
    public function connect($host, $queryport, $username, $password)
    {
        try {
            if ($host && $queryport && $username && $password) {
                $this->tsAdmin = new TS3Admin($host, $queryport);
                $connect = $this->tsAdmin->connect();
                if ($this->tsAdmin->getElement('success', $connect)) {
                    $this->tsAdmin->login($username, $password);
                }
                $this->tsAdmin($this->tsAdmin);
                return $this->tsAdmin;
            }
        } catch (Exception $ex) {
            return false;
        }
        return false;
    }

    public function selectInstance($port)
    {
        $connect = $this->tsAdmin()->selectServer($port);
        $server = $this->tsAdmin()->serverInfo();
        if (!$connect || $this->errorTeamSpeak($server)) {
            unset($this->tsAdmin);
            return false;
        }
        return true;
    }

    public function setName($name = "BOT")
    {
        if ($this->tsAdmin() && $name) {
            return $this->tsAdmin()->setName($name);
        }
        return false;
    }

    public function desconect()
    {
        if ($this->tsAdmin() && $this->instance) {
            $this->tsAdmin()->logout();
            unset($this->tsAdmin);
            return true;
        }
        return false;
    }

    /** MESSAGE */
    public function readChatMessage($type, $keepalive, $tag = "!", $cid = -1, $tracert = null)
    {
        $info = [];
        if ($this->tsAdmin()) {
            $message = $this->tsAdmin()->readChatMessage($type, $keepalive);
            if ($message) {
                $command = explode(" ", $message['data']['msg']);
                if (strpos($command[0], $tag) !== false) {
                    foreach ($command as $cmd) {
                        $info['commands'][] = trim($cmd);
                    }
                    $info['msg'] = $message['data']['msg'];
                    $info['invoker'] = ['id' => $message['data']['invokerid'], 'name' => $message['data']['invokername']];
                    return $info;
                }
            }
        }
        return false;
    }

    public function readEventServer($keepalive, $tag = "!", $cid = -1, $tracert = null)
    {
        $info = [];
        if ($this->tsAdmin()) {
            $message = $this->tsAdmin()->readEventServer($keepalive, $cid = -1, $tracert = null);
            if ($message['success'] == 1) {
                $command = explode(" ", $message['data']['msg']);
                if (strpos($command[0], $tag) !== false) {
                    foreach ($command as $cmd) {
                        $info['commands'][] = trim($cmd);
                    }
                }

                $info['msg'] = $message['data']['msg'];
                $info['invoker'] = [
                    'id' => $message['data']['invokerid'],
                    'name' => $message['data']['invokername'],
                    'client_nickname' => $message['data']['client_nickname'],
                    'client_unique_identifier' => $message['data']['client_unique_identifier'],
                    'client_totalconnections' => $message['data']['client_totalconnections'],
                    'client_database_id' => $message['data']['client_database_id'],
                    'clid' => $message['data']['clid']
                ];
                return $info;
            }
        }
        return false;
    }

    public function sendMessage($mode, $target, $msg)
    {
        if ($this->tsAdmin()) {
            return $this->tsAdmin()->sendMessage($mode, $target, $msg);
        }
        return false;
    }

    public function isServerAdmin($clid)
    {
        $permissions = [
            'b_virtualserver_token_list',
            'b_virtualserver_token_add',
            'b_virtualserver_token_use',
            'b_virtualserver_token_delete',
            'b_virtualserver_modify_name',
            'b_virtualserver_modify_welcomemessage',
            'b_virtualserver_modify_reserved_slots',
            'b_virtualserver_modify_hostmessage',
            'b_virtualserver_modify_hostbanner',
            'b_virtualserver_modify_default_messages'
        ];

        $client = $this->tsAdmin()->clientInfo($clid);
        $serverGroup = explode(',', $client['data']['client_servergroups']);
        $countPerm = 0;
        foreach ($serverGroup as $cligrp) {
            $groupPerm = $this->tsAdmin()->serverGroupPermList($cligrp, true);
            foreach ($groupPerm['data'] as $prm) {
                if (in_array($prm['permsid'], $permissions) ) {
                    $countPerm++;
                }
            }
        }

        if (count($permissions) == $countPerm) {
            return ['success' => true];
        }
        return ['success' => false, 'permissions' => $permissions];
    }

    public function isAdmin($clid, $data, $claimed = null)
    {
        $client = $this->tsAdmin()->clientInfo($clid);
        $groupsClient = explode(',', $client['data']['client_servergroups']);
        $return = [ 'success' => false, 'sgroup' => ['admin' => false, 'claimed' => false] ];
        foreach ($groupsClient as $gp) {
            if (in_array($gp, $data)) {
                if($gp == $claimed){
                    $return['sgroup']['claimed'] = true;
                } else {
                    $return['sgroup']['admin'] = true;
                }
            }
        }

        if($return){
            $return['success'] = true;
            return $return;
        }
        return ['success' => false];
    }

    public function errorTeamSpeak($data)
    {
        if ($data) {
            if ($data['errors']) {
                return true;
            }
            return false;
        }
    }
}
