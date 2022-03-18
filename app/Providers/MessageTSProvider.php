<?php 

namespace App\Providers;
use App\Interfaces\IMessage;

class MessageTSProvider implements IMessage{

    private $msg;

    public function debug($message){
        $this->msg = "[B][COLOR=#CC00FF]DEBUG:[/COLOR] [COLOR=#0066FF]{$message}[/COLOR][/B]";
        return $this->msg;
    }

    public function success($message){
        $this->msg = "[B][COLOR=GREEN]{$message}[/COLOR][/B]";
        return $this->msg;
    }

    public function info($message){
        $this->msg = "[B][COLOR=#339FFF]{$message}[/COLOR][/B]";
        return $this->msg;
    }

    public function alert($message){
        $this->msg = "[B][COLOR=#FF5733]{$message}[/COLOR][/B]";
        return $this->msg;
    }

    public function error($message){
        $this->msg = "[B][COLOR=RED]{$message}[/COLOR][/B]";
        return $this->msg;
    }

    public function separetor(){
        $this->msg = "[COLOR=#cccccc]--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------[/COLOR]\n";
        return $this->msg;
    }

    public function custom($message, $color)
    {
        $this->msg = sprintf("[COLOR=$color][B]{$message}[/B][/COLOR]");
        return $this->msg;
    }

    public function table(array $data)
    {
        $line = "[table]";
        foreach($data as $column){
            $line .= "[tr]";
            foreach($column as $row){
                $line .= "[td]{$row}[/td]";
            }
            $line .= "[/tr]";
        }
        $line .= "[/table]";
        $this->msg = $line;
        return $this->msg;
    }
}
