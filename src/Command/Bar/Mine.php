<?php

namespace Botlife\Command\Bar;

use \Botlife\Entity\Bar\Item\Pickaxe;

class Mine extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!]mine$/i',
    );
    public $action    = 'run';
    public $code      = 'mine';
    
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
        $bar = \Botlife\Application\Storage::loadData('bar');
        $user = $bar->users->{strtolower($event->auth)};
        if (!$user->inventory->hasItem(new Pickaxe)) {
            $this->respondWithPrefix(
                'You need to have a pickaxe in order to mine!'
            );
            return;
        }
    }

}
