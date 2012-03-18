<?php

namespace Botlife\Entity\Bar\Item\Bar;

class MithrilBar extends \Botlife\Entity\Bar\Item\Bar
{

    public $id         = 2359;
    public $name       = 'Mithril bar';
    public $pluralName = 'Mithril bars';
    public $alias      = array('mith');
    public $smithDeps  = array('MithrilOre' => 1, 'Coal' => 4);
    public $gePrice    = true;

}
