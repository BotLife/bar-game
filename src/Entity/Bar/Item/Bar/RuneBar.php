<?php

namespace Botlife\Entity\Bar\Item\Bar;

class RuneBar extends \Botlife\Entity\Bar\Item\Bar
{

    public $id         = 2363;
    public $name       = 'Rune bar';
    public $pluralName = 'Rune bars';
    public $alias      = array('rune', 'runite');
    public $smithDeps  = array('RuneOre' => 1, 'Coal' => 8);
    public $gePrice    = true;

}
