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
    	'\Botlife\Command\Bar\Buy',
    
        '\Botlife\Command\Bar\Admin\Give',
    );
    
    public $events  = array(
    	'loopIterate',
    	'onCtcpRequest'
    );
    
    public function __construct()
    {
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\BronzeBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\IronBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\MithrilBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\AdamantBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\GoldBar);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Bar\RuneBar);
        
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\Tin);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\Copper);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\Coal);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\MithrilOre);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\AdamantOre);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\GoldOre);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Ore\RuneOre);
        
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Pickaxe\BronzePickaxe);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Pickaxe\RunePickaxe);
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Pickaxe\DragonPickaxe);
        
        ItemDb::loadItem(new \Botlife\Entity\Bar\Item\Coin);
        parent::__construct();
    }
    
    public $lastTimerRun = 0;
    
    public function loopIterate()
    {
        if ((time() - $this->lastTimerRun) >= 1) {
            if ((time() - ItemDb::$geLastUpdate) >= ItemDB::$geUpdateInterval
              || ItemDb::$geNeedsUpdate) {
                ItemDb::runUpdates();
            }
            $this->lastTimerRun = time();
        }
    }
    
    public function onCtcpRequest(\Ircbot\Command\CtcpRequest $event)
    {
        if ($event->message == 'VERSION') {
            $reply = new \Ircbot\Command\CtcpReply(
            $event->mask->nickname,
                    'VERSION The Bar Game version 1.0.2'
            );
            $bot = \Ircbot\Application::getInstance()->getBotHandler()
            ->getBotById($event->botId);
            $bot->sendRawData($reply);
        }
    }

}
