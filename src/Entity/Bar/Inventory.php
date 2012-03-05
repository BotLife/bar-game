<?php

namespace Botlife\Entity\Bar;

use \Botlife\Entity\Bar\Item\AItem;

class Inventory
{

    public $items = array();

    public function hasItem(AItem $item)
    {
        return ($this->getItemAmount($item));
    }

    public function getItemAmount(AItem $item)
    {
        return isset($this->items[get_class($item)])
            ? $this->items[get_class($item)] : 0;
    }

    public function setItemAmount(AItem $item, $amount)
    {
        $this->items[get_class($item)] = $amount; 
    }
    
    public function incItemAmount(AItem $item, $amount)
    {
        $this->setItemAmount($item, $this->getItemAmount($item) + $amount);
    }
    
    public function getItemList()
    {
        return $this->items;
    }

}
