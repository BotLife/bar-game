<?php

namespace Botlife\Command\Bar;

use \Botlife\Entity\Bar\Item\Bar\BronzeBar;

class Bar extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!]bar$/i',
    );
    public $action    = 'run';
    public $code      = 'bar';
    
    public $needsAuth = true;
    
    const STATE_GET   = 1;
    const STATE_LOSE  = 2;
    
    public function run($event)
    {
        $this->detectResponseType($event->message);
        $bar = \Botlife\Application\Storage::loadData('bar');
        if (!$event->auth) {
            $this->respondWithPrefix(
                'In order to use bar you need to be logged in to NickServ'
            );
            return;
        }
        if (!isset($bar->users[strtolower($event->auth)])) {
            $this->respondWithPrefix(
                'So you wan\'t to play The Bar Game? Starting is very simple! '
                    . 'Simply use !playbar'
            );
            return;
        }
        $userId = strtolower($event->auth);
	    $user = $bar->users->{$userId};
        if (($user->lastPlayed + $user->waitTime) > time()) {
            $waitTime = ($user->lastPlayed + $user->waitTime) - time();
            $this->respondWithPrefix(
                'You still need to wait ' . gmdate('i:s', $waitTime)
                    . ' seconds before you can use bar again'
            );
            return;
        }
        $this->playBar($user, $bars);
        $this->respondWithPrefix(
            $this->getMessage($event->mask->nickname, $bars) . ' '
                . 'You now have '
                . $user->inventory->getItemAmount(new BronzeBar) . ' bars.'
        );
        $bar->users->$userId = $user;
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
    public function playBar(&$user, &$bars)
    {
        var_dump($this->determineState($user));
        $bars = round(mt_rand(1, 5) * 100 * 0.63, 0);
        $user->inventory->incItemAmount(new BronzeBar, $bars);
        $user->lastPlayed = time();
        $user->waitTime   = round(mt_rand(5, 15) * 60 * 0.91, 0);
    }
    
    // Determines if a player shoud win or lose bars
    public function determineState($user)
    {
        $items  = array();
        $items[self::STATE_LOSE] = 10;
        $items[self::STATE_GET] = true;
        $chance = array();
        $left   = 100;
        $amount = 0;
        foreach ($items as $state => $percentage) {
            if (is_numeric($percentage)) {
                $left -= $percentage;
            } elseif (is_bool($percentage)) {
                ++$amount;
            }
        }
        $each   = floor($left / $amount);
        foreach ($items as $state => $percentage) {
            if (is_bool($percentage)) {
                $items[$state] = $each;
            }
        }
        foreach ($items as $state => $percentage) {
            for ($i = 0; $i < $percentage; ++$i) {
                $chance[] = $state;
            }
        }
        return $chance[mt_rand(0, 99)];
    }
    
    public function getMessage($user, $bars)
    {
        $data = parse_ini_file('bar-messages.ini');
        $message = $data['message'][array_rand($data['message'])];
        return vsprintf($message, func_get_args());
    }

}
