<?php

namespace Botlife\Command\Bar\Admin;

class Give extends \Botlife\Command\ACommand
{

    public $regex = array(
        '/^give (?P<player>.+) (?P<amount>\d+) (?P<item>.+)$/i'
    );
    
    public $action          = 'give';
    public $needsAdmin      = true;
    public $needsSpamfilter = false;

    public function give($event)
    {   
        $bar = \Botlife\Application\Storage::loadData('bar');
        $user = $bar->users->{strtolower($event->matches['player'])};
        if (!$user) {
            return;
        }
        $itemClass = str_replace(' ', '', ucwords($event->matches['item']));
        $item = '\Botlife\Entity\Bar\Item\\' . $itemClass;
        if (!class_exists($item)) {
            return;
        }
        $item = new $item;
        $user->inventory->incItemAmount($item, (int) $event->matches['amount']);
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
}
