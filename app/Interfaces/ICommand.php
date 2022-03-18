<?php

namespace App\Interfaces;
use App\Interfaces\ITSAdmin;
use App\Interfaces\IMessage;

interface ICommand
{

    /** LIST COMMANDS */
    public function help(ITSAdmin $tsAdmin, IMessage $message, Array $data = null);

    /** MASS POKE */
    public function mp(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /** MASS MOVE */
    public function mm(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /** MASS MOVE ONLY USER OFFLINE */
    public function mmoff(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /** MASS KICK */
    public function mk(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /** MASS KICK ONLY USER OFFLINE */
    public function mkoff(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /** MASS KICK CHANNEL */
    public function mck(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /** MOVE GROUP */
    public function mg(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /** MOVE TRACK GROUP */
    public function mtg(ITSAdmin $tsAdmin, IMessage $message, Array $data);

    /**REMOVE PRIORITY SPEAK */
    public function rps(ITSAdmin $tsAdmin, IMessage $message, Array $data = null);

    /** CREATE GRUOP ADMIN */
    public function cgadmin(ITSAdmin $tsAdmin, IMessage $message, Array $data);
   
}
