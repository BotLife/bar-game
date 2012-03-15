<?php

namespace Botlife\Entity\Bar\Item\Bar;

class RuneBar extends \Botlife\Entity\Bar\Item\Bar
{

    public $id         = 102;
    public $name       = 'Rune bar';
    public $pluralName = 'Rune bars';
    public $smithDeps  = array('RuneOre' => 1, 'Coal' => 8);

}
