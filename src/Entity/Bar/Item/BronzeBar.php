<?php

namespace Botlife\Entity\Bar\Item;

class BronzeBar extends Bar
{

    public $id        = 101;
    public $name      = 'Bronze bar';
    public $smithDeps = array('Tin' => 1, 'Copper' => 1);

}
