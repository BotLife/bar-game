<?php

namespace Botlife\Entity\Bar\Item;

class Ore extends AItem
{

    /**
     * Possible values are:
     * -1                 = Determined at the time being used
     * <percentage>       = The chance the ore will be chosen in percentage
     */
    public $mineChance = -1;

}
