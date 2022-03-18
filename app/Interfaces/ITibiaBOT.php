<?php

namespace App\Interfaces;

interface ITibiaBOT{

    public function friends();
    public function ally();

    public function hunteds();
    public function enemy();

    public function neutrals();
    public function deaths();
    public function uplevel();
    public function charconnect();

    public function news($msg);

    public function claimed();
}

?>