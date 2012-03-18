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
        $item = \Botlife\Entity\Bar\ItemDb::getItem($event->matches['item']);
        if (!$item) {
            return;
        }
        $user->inventory->incItemAmount($item, (int) $event->matches['amount']);
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
}
