<?php

namespace App\Cases;

use App\Interfaces\ICommand;
use App\Interfaces\IMessage;
use App\Interfaces\ITSAdmin;
use App\Models\Bot;

class CommandCase
{
    private $command;
    private $tsAdmin;
    private $message;

    public function __construct(ICommand $command, ITSAdmin $tsAdmin, IMessage $message)
    {
        $this->command = $command;
        $this->tsAdmin = $tsAdmin;
        $this->message = $message;
    }

    public function tsconnect($host, $port, $queryport, $username, $password, $name)
    {
        $this->tsAdmin->connect($host, $queryport, $username, $password);
        if ($this->tsAdmin->selectInstance($port)) {
            $this->tsAdmin->setName($name);
        }
    }

    /** LIST COMMANDS */
    public function headle($tag)
    {
        $message = $this->tsAdmin->readEventServer(true, $tag->tag_command);

        if (!$message || !isset($message['invoker'])) {
            return;
        }

        $message['bot']['id'] = $tag->id;
        $message['bot']['tag'] = $tag->tag_command;

        $groups = Bot::find($tag->id);
        $this->tsAdmin->tsAdmin()->sendMessage(1, $message['invoker']['clid'], $this->message->success("Olá {$message['invoker']['client_nickname']} você pode enviar comandos por aqui!\n\n".  $this->message->info("Digite: {$tag->tag_command}help para visualizar os comandos.")));
        
        if (array_key_exists('commands', $message)) {
            $cmd = strtolower(str_replace($tag->tag_command, '', $message['commands'][0]));
            if (strpos($message['commands'][0], $tag->tag_command) !== false && !array_key_exists($cmd, configure('commands')['commands'])) {
                $this->tsAdmin->tsAdmin()->sendMessage(1, $message['invoker']['id'], $this->message->error('Comando não encontrado! ') .  $this->message->info('Para visualizar os comandos digite: ') . $this->message->success($tag->tag_command . "help"));
            } else {
                $clidData = $this->tsAdmin->tsAdmin()->clientFind($message['invoker']['name']);

                foreach($clidData['data'] as $cid){
                    if($message['invoker']['name']  == $cid['client_nickname']){
                        $clid = $cid['clid'];
                    }
                }

                $svAdmin = $this->tsAdmin->isServerAdmin($clid);
                $isAdmin = $this->tsAdmin->isAdmin($clid, [$groups->sgid_bot, $groups->sgid_claimed], $groups->sgid_claimed);
                if(!$svAdmin['success'] && !$isAdmin['success']){
                    $this->tsAdmin->tsAdmin()->sendMessage(1, $message['invoker']['id'], $this->message->error($message['invoker']['name'] . ": " .'Insufficient permissions!'));
                    return null;
                }

                if (array_key_exists($cmd, configure('commands')['commands'])) {
                    //chamada dos comandos da classe
                    $claimd = ['claimed','rmclaimed'];
                    if($isAdmin['sgroup']['claimed'] && !$isAdmin['sgroup']['admin'] && !$svAdmin['success'] && !in_array($cmd, $claimd)){
                        $this->tsAdmin->tsAdmin()->sendMessage(1, $message['invoker']['id'], $this->message->error($message['invoker']['name'] . ": " .'Insufficient permissions!'));
                        return null;
                    }
                    $this->command->$cmd($this->tsAdmin, $this->message, $message);
                }
            }
        }
    }
}
