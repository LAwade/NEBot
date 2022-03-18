<?php

namespace App\Cases;

use App\Interfaces\IMessage;
use App\Interfaces\ITSAdmin;
use Exception;

class TSAdminCase
{
    /**
     * Connection with ServerQuery
     * @var ITSAdmin $tsAdmin
     */
    private $tsAdmin;

    /**
     * Connection with ServerQuery
     * @var IMessage $tsAdmin
     */
    private $message;

    public function __construct(ITSAdmin $tsAdmin, IMessage $message)
    {
        $this->tsAdmin = $tsAdmin;
        $this->message = $message;
    }
}