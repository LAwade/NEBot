<?php

namespace App\Interfaces;

interface ITibia {
    public function dataGuilds($world, $src, $server, $url, $host, $guilds);
    public function dataNeutrals($world, $src, $server, $url, $host);
    public function dataDeaths($world, $src, $server, $url, $host);
}

?>