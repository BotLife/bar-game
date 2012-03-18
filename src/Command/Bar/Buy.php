<?php

namespace Botlife\Command\Bar;

use \Botlife\Entity\Bar\ItemDb;

class Buy extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!@]buy(( (?P<amount>\d{1,}))? (?P<item>.+))?$/i',
    );
    public $action    = 'run';
    public $code      = 'buy';
    
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
                'Please define what item you want to buy. Example: '
                    . '!buy rune pickaxe'
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
        if (isset($event->matches['amount'])
          && (int) $event->matches['amount']) {
            $amount = (int) $event->matches['amount'];
        } else {
            $amount = 1;
        }
        $price = $price * $amount;
        $coin = new \Botlife\Entity\Bar\Item\Coin;
        $user = $bar->users[strtolower($event->auth)];
        if (!$user->inventory->hasItemAmount($coin, $price)) {
            $this->respondWithPrefix(sprintf(
            	'You need %s %s',
            	number_format($price), $coin->getName($price)
            ));
            return;
        }
        $this->buy($user, $item, $price, $amount);
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
    public function buy($user, $item, $price, $amount)
    {
        $c   = new \Botlife\Application\Colors;
        $coin = new \Botlife\Entity\Bar\Item\Coin;
        $math = new \Botlife\Utility\Math;
        $user->inventory->decItemAmount($coin, $price);
        $user->inventory->incItemAmount($item, $amount);
        $this->respondWithPrefix(sprintf(
            'You just bought ' . $c(3, '%s %s') . $c(12, ' for ') 
                . $c(3, '%s %s.'),
            number_format($amount), $item->getName($amount),
            $math->alphaRound($price, 2), $coin->getName($price)
        ));
    }

}
