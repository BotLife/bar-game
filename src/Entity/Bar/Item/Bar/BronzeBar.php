<?php

namespace Botlife\Entity\Bar\Item\Bar;

class BronzeBar extends \Botlife\Entity\Bar\Item\Bar
{

    public $id         = 2349;
    public $name       = 'Bronze bar';
    public $pluralName = 'Bronze bars';
    public $alias      = array('brons', 'bronze');
    public $smithDeps  = array('Tin' => 1, 'Copper' => 1);
    public $gePrice    = true;

}
