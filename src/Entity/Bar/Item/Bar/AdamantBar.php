<?php

namespace Botlife\Entity\Bar\Item\Bar;

class AdamantBar extends \Botlife\Entity\Bar\Item\Bar
{

    public $id         = 2361;
    public $name       = 'Adamant bar';
    public $pluralName = 'Adamant bars';
    public $alias      = array('addy');
    public $smithDeps  = array('AdamantOre' => 1, 'Coal' => 6);
    public $gePrice    = true;

}
