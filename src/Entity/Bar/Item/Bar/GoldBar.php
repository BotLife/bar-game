<?php

namespace Botlife\Entity\Bar\Item\Bar;

class GoldBar extends \Botlife\Entity\Bar\Item\Bar
{

    public $id         = 2357;
    public $name       = 'Gold bar';
    public $pluralName = 'Gold bars';
    public $alias      = array('gold');
    public $smithDeps  = array('GoldOre' => 1);
    public $gePrice    = true;

}
