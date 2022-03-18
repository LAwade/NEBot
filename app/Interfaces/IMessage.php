<?php

namespace App\Interfaces;

interface IMessage {
    public function debug($message);
    public function success($message);
    public function info($message);
    public function alert($message);
    public function error($message);
    
    public function table(array $data);
    public function separetor();
    public function custom($message, $color);
}
