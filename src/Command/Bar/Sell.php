<?php

namespace Botlife\Command\Bar;

use \Botlife\Entity\Bar\ItemDb;

class Sell extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!@]sell(( (?P<amount>[0-9]{1,}))? (?P<item>.+))?$/i',
    );
    public $action    = 'run';
    public $code      = 'sell';
    
    public $needsAuth = true;
    
    public function run($event)
    {
        $this->detectResponseType($event->message);
        $c   = new \Botlife\Application\Colors;
        $bar = \Botlife\Application\Storage::loadData('bar');
        $user = $bar->users->{strtolower($event->auth)};
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
        if (!isset($event->matches['item'])) {
            $this->respondWithPrefix(
                'So you\'re into earning some money? Sure, but you need to '
                	. 'define what you want to sell. Example: '
                    . '!sell bronze bar'
            );
            return;
        }
        $item = ItemDb::getItem($event->matches['item']);
        if (!$item) {
            $this->respondWithPrefix(
                'Damn I don\'t know that kind of item...'
            );
            return;
        }    
        $price = ItemDb::getPrice($item);
        if (!$price) {
            $this->respondWithPrefix(sprintf(
                'I don\'t know the price of %s at the moment...',
                $item->getName(0)
            ));
            return;
        }
        if (isset($event->matches['amount'])) {
            $amount = (int) $event->matches['amount'];
        } else {
            $amount = 1;
        }
        $user = $bar->users[strtolower($event->auth)];
        if (!$user->inventory->hasItemAmount($item, $amount)) {
            $this->respondWithPrefix(sprintf(
            	'You don\'t have %s %s',
            	$amount, $item->getName($amount)
            ));
            return;
        }
        $this->sell($user, $item, ($price * $amount), $amount);
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
    public function sell($user, $item, $price, $amount)
    {
        $c   = new \Botlife\Application\Colors;
        $coin = new \Botlife\Entity\Bar\Item\Coin;
        $math = new \Botlife\Utility\Math;
        $user->inventory->incItemAmount($coin, $price);
        $user->inventory->decItemAmount($item, $amount);
        $this->respondWithPrefix(sprintf(
            'You just sold ' . $c(3, '%s %s') . $c(12, ' for ') 
                . $c(3, '%s %s.'),
            number_format($amount), $item->getName($amount),
            $math->alphaRound($price,2), $coin->getName($price)
        ));
    }

}
