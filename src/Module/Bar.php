<?php

namespace Botlife\Module;

use \Botlife\Entity\Bar\ItemDb;

class Bar extends AModule
{

    public $commands = array(
        /*'\Botlife\Command\Bar\Bar',*/ // Disabled
        '\Botlife\Command\Bar\PlayBar',
        '\Botlife\Command\Bar\Inv',
        '\Botlife\Command\Bar\Mine',
        '\Botlife\Command\Bar\Smith',
        
    	'\Botlife\Command\Bar\Sell',
    
        '\Botlife\Command\Bar\Admin\Give',
    );
    
    public $events  = array(
    	'loopIterate',
    );
    
    public function __construct()
    {
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\BronzeBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\IronBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\GoldBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\RuneBar);
        
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\Tin);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\Copper);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\Coal);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\GoldOre);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\RuneOre);
        
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Pickaxe\BronzePickaxe);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Pickaxe\RunePickaxe);
        
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Coin);
        parent::__construct();
    }
    
    public $lastTimerRun = 0;
    
    public function loopIterate()
    {
        if ((time() - $this->lastTimerRun) >= 10) {
            if ((time() - ItemDb::$geLastUpdate) >= ItemDB::$geUpdateInterval
              || ItemDb::$geNeedsUpdate) {
                ItemDb::runUpdates();
            }
            $this->lastTimerRun = time();
        }
    }

}
