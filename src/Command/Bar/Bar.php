<?php

namespace Botlife\Command\Bar;

use \Botlife\Entity\Bar\Item\BronzeBar;

class Bar extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!]bar$/i',
    );
    public $action    = 'run';
    public $code      = 'bar';
    
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
        $userId = strtolower($event->auth);
        
        if (!isset($bar->users[strtolower($event->auth)])) {
            \Ircbot\msg('#BotLife.Team', 'New bar user named: ' . $event->auth);
            $user = new \Botlife\Entity\Bar\User;
            $user->bars       = 0;
            $user->lastPlayed = 0;
            $user->waitTime   = 0;
        } else {
            $user = $bar->users->{$userId};
        }
        if (($user->lastPlayed + $user->waitTime) > time()) {
            $waitTime = ($user->lastPlayed + $user->waitTime) - time();
            $this->respondWithPrefix(
                'You still need to wait ' . gmdate('i:s', $waitTime)
                    . ' seconds before you can use bar again'
            );
            return;
        }
        $bars = round(mt_rand(1, 5) * 100 * 0.63, 0);
        $user->inventory->incItemAmount(new BronzeBar, $bars);
        $user->lastPlayed = time();
        $user->waitTime   = round(mt_rand(5, 15) * 60 * 0.91, 0);
        $this->respondWithPrefix(
            $this->getMessage($event->mask->nickname, $bars) . ' '
                . 'You now have '
                . $user->inventory->getItemAmount(new BronzeBar) . ' bars.'
        );
        $bar->users->$userId = $user;
        var_dump($bar);
        \Botlife\Application\Storage::saveData('bar', $bar);
        
        //var_dump(new \StorageObject);
    }
    
    public function getMessage($user, $bars)
    {
        $data = parse_ini_file('bar-messages.ini');
        $message = $data['message'][array_rand($data['message'])];
        return vsprintf($message, func_get_args());
    }

}
