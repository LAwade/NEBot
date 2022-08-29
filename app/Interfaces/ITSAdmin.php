<?php 

namespace App\Interfaces;

use App\Shared\TS3Admin;

interface ITSAdmin {
    /** CONNECT */
    public function connect($host, $queryport, $username, $password);
    public function selectInstance($port);
    public function setName($name);
    public function desconect();

    /** TSADMIN */
    public function tsAdmin();
    public function isServerAdmin($clid);
    public function isAdmin($clid, $data);

    /** MESSAGE */
    public function readChatMessage($type, $keepalive, $cid = -1, $tracert = null);

    public function readEventServer($keepalive, $tag = "!", $cid = -1, $tracert = null);
    
    /** ERROR */
    public function errorTeamSpeak($data);
}
