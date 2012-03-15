<?php

namespace Botlife\Entity\Bar\Item\Bar;

class GoldBar extends \Botlife\Entity\Bar\Item\Bar
{

    public $id         = 105;
    public $name       = 'Gold bar';
    public $pluralName = 'Gold bars';
    public $smithDeps  = array('GoldOre' => 1);

}
