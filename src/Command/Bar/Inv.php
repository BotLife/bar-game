<?php

namespace Botlife\Command\Bar;

class Inv extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!@]inv(entory)?$/i',
    );
    public $action    = 'run';
    public $code      = 'inv';
    
    public $needsAuth = true;
    
    public function run($event)
    {
        $this->detectResponseType($event->message, $event->target);
        if (!$event->auth) {
            $this->respondWithPrefix(
                'In order to use bar you need to be logged in to NickServ'
            );
            return;
        }
        $bar   = \Botlife\Application\Storage::loadData('bar');
        $user  = $bar->users->{strtolower($event->auth)};
        $items = $user->inventory->getItemList();
        $math  = new \Botlife\Utility\Math;
        $tmp   = array();
        foreach ($items as $item => $amount) {
            $class = new $item;
            $alpha = $math->alphaRound($amount);
            if ((string) $amount != $alpha) {
                $tmp[$class->getName($amount)] = array(
                    number_format($amount),
                    array(
                        $alpha
                    )
                );
            } else {
                $tmp[$class->getName($amount)] = number_format($amount);
            }
        }
        $this->respondWithInformation(
            $tmp
        );
    }

}
