<?php

namespace Botlife\Entity\Bar;

use \Botlife\Entity\Bar\Item\AItem;

class Inventory
{

    public $items = array();

    public function hasItem(AItem $item)
    {
        if (isset($item->id)) {
            return ($this->getItemAmount($item));
        } else {
            foreach ($this->items as $sort => $amount) {
                $sort = new $sort;
                if ($sort instanceof $item) {
                    return true;
                }
            }
        }
        return false;
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
    
    public function incItemAmount(AItem $item, $amount = 1)
    {
        $this->setItemAmount($item, $this->getItemAmount($item) + $amount);
    }
    
    public function getBestOfKind(AItem $item)
    {
        if (!$this->hasItem($item)) {
            return false;
        }
        $best = array(0, '');
        foreach ($this->items as $sort => $amount) {
            $sort = new $sort;
            if ($sort instanceof $item) {
                if ($best[0] < $sort->quality) {
                    $best = array($sort->quality, $sort);
                }
            }
        }
        return $best[1];
    }
    
    public function getItemList()
    {
        return $this->items;
    }

}
