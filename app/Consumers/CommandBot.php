<?php

namespace App\Consumers;

use App\Cases\CommandCase;
use App\Models\Bot;
use App\Models\Teamspeak;
use App\Providers\CommandTSProvider;
use App\Providers\MessageTSProvider;
use App\Providers\TSAdminProvider;

class CommandBot
{
    private $command;

    public function start($idbot)
    {
        $bot = Bot::find($idbot);

        if ($bot) {
            $teamspeak = Teamspeak::where('fk_id_bot', $bot->id)->first();
            $this->command = new CommandCase(new CommandTSProvider(), new TSAdminProvider(), new MessageTSProvider());
            $this->command->tsconnect($teamspeak->host, $teamspeak->port, $teamspeak->queryport, $teamspeak->querylogin, $teamspeak->querypassword, $bot->name);
            while (true) {
                $this->command->headle($bot);
            }
        }
    }
}
