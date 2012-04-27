<?php

namespace Botlife\Entity\Bar;

use Botlife\Entity\Bar\ItemDb;

class ItemSearch extends \Botlife\Entity\SearchEngine
{
    
    public $id       = 'the-bar-game';
    public $priority = 25;
    public $aliases  = array('bar');
    
    public function search($searchTerms, $results = 1, $filters = array())
    {
        $item = ItemDb::getItem($searchTerms);
        if (!$item) {
            return false;
        }
        
        $info = new \StdClass;
        $info->searchTerms = $searchTerms;
        $info->searchEngine = $this;
        $info->entries = array();
        
        $result = new \StdClass;
        $result->id    = $item->id;
        $result->title = $item->name;
        if ($price = ItemDb::getPrice($item)) {
            $result->price = new \StdClass;
            $result->price->amount = $price;
            $result->price->currency = ItemDb::getItem('coin')->getName($price);
        }
        
        $result->rating = new \StdClass;
        $result->rating->average = $item->quality * 2;
        if ($result->rating->average > 100) {
            $result->rating->average = 100;
        }
        
        $info->entries[] = $result;

        $info->results = count($info->entries);
        if (!$info->results) {
            return false;
        }
        return $info;
    }
    
}