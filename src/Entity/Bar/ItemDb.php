<?php

namespace Botlife\Entity\Bar;

class ItemDb
{

    private static $_cache;
    
    public static function getItem($name, array $items = array())
    {
        if ($items) {
            foreach ($items as $item) {
                $class = new $item;
                if (!isset(self::$_cache[strtolower($class->name)])) {
                    self::$_cache[strtolower($class->name)] = $item;
                    if ($class->pluralName) {
                        self::$_cache[strtolower($class->pluralName)] = $item;
                    }
                    if (!isset($class->alias)) {
                        continue;
                    }
                    foreach ($class->alias as $alias) {
                        self::$_cache[strtolower($alias)] = $item;
                    }
                }
                
            }
        }
        if (isset(self::$_cache[strtolower($name)])) {
            return new self::$_cache[strtolower($name)];
        }
    }
    
}
