<?php

namespace Botlife\Command\Bar;

use \Botlife\Entity\Bar\Item\BronzePickaxe;

class PlayBar extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!]playbar$/i',
    );
    public $action    = 'run';
    public $code      = 'playbar';
    
    public $needsAuth = true;
    
    const STATE_GET   = 1;
    const STATE_LOSE  = 2;
    
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
        if (isset($bar->users[strtolower($event->auth)])) {
            $this->respondWithPrefix(
                'You are already registered.. You don\'t want to start again. '
                    . 'Or do you?'
            );
		    return;
        }
        $userId = strtolower($event->auth);   
        \Ircbot\msg('#BotLife.Team', 'New bar user named: ' . $event->auth);
        $user = new \Botlife\Entity\Bar\User;
        $this->setupPlayer($user);
        $bar->users->$userId = $user;
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
    public function setupPlayer(&$user)
    {
        $c = new \Botlife\Application\Colors;
        $user->lastPlayed = 0;
        $user->waitTime   = 0;
        $user->inventory->incItemAmount(new BronzePickaxe);
        $items            = $user->inventory->getItemList();
        $tmp              = array();
        foreach ($items as $item => $amount) {
            $class = new $item;
            $tmp[$class->name] = $amount;
        }
        $this->respondWithInformation(
            $tmp
        );
        $this->respondWithPrefix(
            'As you can see you have a bronze pickaxe. '
                . 'You\'re now able to play The Bar Game! '
                . 'Lets start the adventure by using !mine'
        );
    }

}
