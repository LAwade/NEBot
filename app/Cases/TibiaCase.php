<?php

namespace App\Cases;

use App\Interfaces\ITibia;
use App\Models\Bot;
use App\Models\TibiaFriendList;
use App\Models\TibiaHuntedList;

class TibiaCase
{

    private $tibia;
    private $servers = [];

    public function __construct(ITibia $tibia)
    {
        $this->tibia = $tibia;
    }

    public function handle()
    {
        $tibia = Bot::join('tibia', 'tibia.fk_id_bot', '=', 'bots.id')
            ->join('tibia_api', 'tibia.fk_id_tibia_api', '=', 'tibia_api.id')
            ->join('tibia_api_server', 'tibia_api.fk_id_tibia_api_server', '=', 'tibia_api_server.id')
            ->join('teamspeaks', 'teamspeaks.fk_id_bot', '=', 'bots.id')
            ->select('tibia.id', 'tibia_api_server.name', 'server', 'src', 'url', 'tibia_api_server.host AS server_api_host')
            ->orderBy('server')
            ->get();

        foreach ($tibia as $guilds) {

            if (in_array($guilds->tibia_guild, $this->servers[$guilds->server])) {
                continue;
            }

            $friends = TibiaFriendList::where('fk_id_tibia', $guilds->id)->get();
            foreach ($friends as $friend) {
                $this->servers[$guilds->server][] = $friend->tibia_guild;
                $this->tibia->dataGuilds($guilds->world, $guilds->src, $guilds->server, $guilds->url, $guilds->server_api_host, $friend->tibia_guild);
            }

            $hunteds = TibiaHuntedList::where('fk_id_tibia', $guilds->id)->get();
            foreach ($hunteds as $hunted) {
                $this->servers[$guilds->server][] = $hunted->tibia_guild;
                $this->tibia->dataGuilds($guilds->world, $guilds->src, $guilds->server, $guilds->url, $guilds->server_api_host, $hunted->tibia_guild);
            }

            $this->tibia->dataNeutrals($guilds->world, $guilds->src, $guilds->server, $guilds->url, $guilds->server_api_host);
            $this->tibia->dataDeaths($guilds->world, $guilds->src, $guilds->server, $guilds->url, $guilds->server_api_host);
        }
    }
}
