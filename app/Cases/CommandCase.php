<?php

namespace App\Cases;

use App\Interfaces\ICommand;
use App\Interfaces\IMessage;
use App\Interfaces\ITSAdmin;

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

        $message['bot']['id'] = $tag->id;
        $message['bot']['tag'] = $tag->tag_command;

        if (!$message || !isset($message['invoker'])) {
            return;
        }

        $this->tsAdmin->tsAdmin()->sendMessage(1, $message['invoker']['clid'], $this->message->success("Olá {$message['invoker']['client_nickname']} você pode enviar comandos por aqui!"));
        if (array_key_exists('commands', $message)) {
            $cmd = strtolower(str_replace($tag->tag_command, '', $message['commands'][0]));
            if (strpos($message['commands'][0], $tag->tag_command) !== false && !array_key_exists($cmd, configure('commands')['commands'])) {
                $this->tsAdmin->tsAdmin()->sendMessage(3, 5, $this->message->error('Comando não encontrado! ') .  $this->message->info('Para visualizar os comandos digite: ') . $this->message->success($tag->tag_command . "help"));
            } else {
                if (array_key_exists($cmd, configure('commands')['commands'])) {
                    //chamada dos comandos da classe
                    $this->command->$cmd($this->tsAdmin, $this->message, $message);
                }
            }
        }
    }
}
