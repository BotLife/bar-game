<?php

namespace Botlife\Command\Bar;

use \Botlife\Entity\Bar\Item\BronzeBar;

class Inv extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!@]inv$/i',
    );
    public $action    = 'run';
    public $code      = 'inv';
    
    public $needsAuth = true;
    
    public function run($event)
    {
        $this->detectResponseType($event->message);
        if (!$event->auth) {
            $this->respondWithPrefix(
                'In order to use bar you need to be logged in to NickServ'
            );
            return;
        }
        $bar   = \Botlife\Application\Storage::loadData('bar');
        $user  = $bar->users->{strtolower($event->auth)};
        $items = $user->inventory->getItemList();
        $tmp   = array();
        foreach ($items as $item => $amount) {
            $class = new $item;
            $tmp[$class->name] = $amount;
        }
        $this->respondWithInformation(
            $tmp
        );
    }

}
