<?php

namespace Botlife\Entity\Bar;

use \Botlife\Entity\Bar\Dao\ItemPrice;

class ItemDb
{

    private static $_cache       = array();
    private static $_items       = array();
    private static $_prices      = array();
    private static $_updatePrice = array();
    private static $_itemToId    = array();
    
    public static $geNeedsUpdate    = false;
    public static $geLastUpdate     = 0;
    public static $geUpdateInterval = 120;
    
    public static function getItem($name)
    {
        if (isset(self::$_cache[strtolower($name)])) {
            return new self::$_cache[strtolower($name)];
        }
        return false;
    }
    
    public static function loadItem($class)
    {
        $debug = new \Botlife\Debug;
        $item = get_class($class);
        $debug->log('Bar', 'ItemDb', 'Loaded item ' . $item . '.');
        if (!isset(self::$_items[$class->id])) {
            self::$_cache[strtolower($class->name)] = $item;
            if ($class->pluralName) {
                self::$_cache[strtolower($class->pluralName)] = $item;
            }
            if (isset($class->alias)) {
                foreach ($class->alias as $alias) {
                    self::$_cache[strtolower($alias)] = $item;
                }
            }
            self::$_items[$class->id] = $class;
            self::$_itemToId[$item] = $class->id;
            if ($class->gePrice) {
                self::$geNeedsUpdate = true;
            }
        }
    }
    
    public static function runUpdates()
    {
        $debug = new \Botlife\Debug;
        foreach (self::$_items as $id => $item) {
            if (!$item->gePrice) {
                continue;
            }
            if (!isset($item->geLastUpdate)) {
                $item->geLastUpdate = 0;
            }
            if ((time() - $item->geLastUpdate) > self::$geUpdateInterval + 10) {
                if (in_array($id, self::$_updatePrice)) {
                    continue;
                }
                self::$_updatePrice[] = $id;
                self::$geNeedsUpdate  = true;
            }
        }
        if (!empty(self::$_updatePrice)) {
            $id = array_shift(self::$_updatePrice);
            $debug->log(
            	'Bar', 'Ge',
            	'Updating price of item ' . self::$_items[$id]->name
            );
            $price = ItemPrice::getPrice($id);
            if (is_numeric($price)) {
                self::$_prices[$id] = $price;
                self::$_items[$id]->geLastUpdate = time();
            }
            if (empty(self::$_updatePrice)) {
                self::$geLastUpdate = time();
                self::$geNeedsUpdate = false;
                $debug->log('Bar', 'Ge', 'All item prices are up-to-date.');
            }
        }        
    }

}
