<?php

namespace Botlife\Entity\Bar;

class User
{

    public $inventory;
    public $waitTime;
    
    public function __construct()
    {
        $this->inventory = new \Botlife\Entity\Bar\Inventory;
    }
    
}
