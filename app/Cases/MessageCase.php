<?php 

namespace App\Cases;
use App\Interfaces\IMessage;

class MessageCase {

    private $message;

    public function __construct(IMessage $message)
    {
        $this->message = $message;
    }

    public function debug($message){
        return $this->message->debug($message);
    }

    public function success($message){
        return $this->message->success($message);
    }

    public function info($message){
        return $this->message->info($message);
    }

    public function alert($message){
        return $this->message->alert($message);
    }

    public function error($message){
        return $this->message->error($message);
    }

    public function separetor(){
        return $this->message->separetor();
    }

    public function custom($message, $color){
        return $this->message->custom($message, $color);
    }
}

?>