<?php

namespace Botlife\Command\Bar;

use Botlife\Entity\Bar\ItemDb;
use Botlife\Entity\Bar\Item\Chest;

class OpenChest extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!]openchest$/i',
    );
    public $action    = 'run';
    public $code      = 'open-chest';
    
    public $needsAuth = true;
    
    public function run($event)
    {
        $this->detectResponseType($event->message, $event->target);
        $c   = new \Botlife\Application\Colors;
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
        $user = $bar->users->{strtolower($event->auth)};
        if (!$user->inventory->hasItem(new Chest)) {
            $this->respondWithPrefix(
                'You can\'t open a chest you don\'t have right?'
            );
            return;
        }
        $this->play($user);
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
    public function play(&$user)
    {
        $c        = new \Botlife\Application\Colors;
        $metDeps  = true;
        $barsMade = 0;
        $moneyLeft = ItemDb::getItem('chest')->worth;
        $items     = array();
        while ($moneyLeft) {
            $possibleItems = ItemDb::getItemsUnderPrice($moneyLeft);
            shuffle($possibleItems);
            if (isset($possibleItems[0])) {
                $item = $possibleItems[0];
                $price = ItemDb::getPrice($item);
                if (!isset($items[get_class($item)])) {
                    $items[get_class($item)] = 1;    
                } else {
                    $items[get_class($item)]++;
                }
                $moneyLeft -= $price;
            } else {
                $item = ItemDb::getItem('coin');
                $items[get_class($item)] = $moneyLeft;
                $moneyLeft -= $moneyLeft;
            }
        }
        
        $itemText = array();
        foreach ($items as $item => $amount) {
            $item = new $item;
            $itemText[] = $c(3, $item->getName($amount)) . $c(12, ' = ')
                . $c(3, $amount);
            $user->inventory->incItemAmount($item, $amount);
        }
        $user->inventory->decItemAmount(ItemDb::getItem('chest'));
        $this->respondWithPrefix(sprintf(
            'You found a chest worth %s coins! With the following items in it: '
            	. '%s',
            number_format(ItemDb::getItem('chest')->worth),
            implode($c(12, ', '), $itemText)
        ));
    }

}
