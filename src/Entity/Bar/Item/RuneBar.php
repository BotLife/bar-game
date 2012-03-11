<?php

namespace Botlife\Entity\Bar\Item;

class RuneBar extends Bar
{

    public $id   = 102;
    public $name = 'Rune bar';
    public $smithDeps = array('RuneOre' => 1, 'Coal' => 8);

}
