<?php

namespace Botlife\Command\Bar;

use Ircbot\Type\MessageCommand;

use \Botlife\Entity\Bar\Item\Pickaxe;

class Mine extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!]mine( )?(?P<ore>.+)?$/i',
    );
    public $action    = 'run';
    public $code      = 'mine';
    
    public $needsAuth = true;
    
    public $ores      = array(
        'Tin', 'Copper', 'Coal', 'GoldOre', 'RuneOre'    
    );
    
    public function run($event)
    {
        $this->detectResponseType($event->message);
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
        if (!$user->inventory->hasItem(new Pickaxe)) {
            $this->respondWithPrefix(
                'You need to have a pickaxe in order to mine!'
            );
            return;
        }
        if (($user->lastPlayed + $user->waitTime) > time()) {
            $waitTime = ($user->lastPlayed + $user->waitTime) - time();
            $this->respondWithPrefix(
                'You still need to wait ' . gmdate('i:s', $waitTime)
                    . ' seconds before you can play The Bar Game again'
            );
            return;
        }
        if (isset($event->matches['ore'])) {
            $oreAliases = array();
            foreach ($this->ores as $ore) {
                $item = '\Botlife\Entity\Bar\Item\Ore\\' . $ore;
                $item = new $item;
                $oreAliases[$item->name] = $item;
                if (isset($item->alias)) {
                    foreach ($item->alias as $alias) {
                        $oreAliases[strtolower($alias)] = $item;
                    }
                }
            }
            unset($ore);
            if (!isset($oreAliases[strtolower($event->matches['ore'])])) {
                $this->respondWithPrefix(sprintf(
            		'Mmm I don\'t know a ore named ' .  $c(3, '%s') . $c(12, '.'),
                    strtolower($event->matches['ore'])
                ));
                return;
            }
            
            if (mt_rand(1, 4) == 2) {
                $ore = $oreAliases[strtolower($event->matches['ore'])];
            }
        };
        $this->mine($event, $user, (isset($ore)) ? $ore : null);
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
    public function mine($event, &$user, $ore)
    {
        $c   = new \Botlife\Application\Colors;
        $pickaxe = $user->inventory->getBestOfKind(new Pickaxe);
        if (!$ore) {
            $ore = $this->randomOre($user);
        }
        $ores = round(mt_rand(1, 5) * 10 * 0.37, 0);
        $ores *= $pickaxe->quality; 
        $ores /= $ore->quality;
        $ores = round($ores);
        $user->inventory->incItemAmount($ore, $ores);
        
        $user->lastPlayed = time();
        $user->waitTime   = round(mt_rand(5, 15) * 60 * 0.91, 0);
        $waitTime = ($user->lastPlayed + $user->waitTime) - time();
        $this->respondWithPrefix(sprintf(
            'You just mined ' . $c(3, '%s %s') . $c(12, ' with your ')
                . $c(3, '%s') . $c(12, '! You now have ') . $c(3, '%s %s')
                . $c(12, '. Only ') . $c(3, '%s')
                . $c(12, ' left till you can mine again!'),
            number_format($ores), $ore->name, $pickaxe->name,
            $user->inventory->getItemAmount($ore), $ore->name,
            gmdate('i:s', $waitTime)
        ));
    }
    
    public function randomOre($user)
    {
        $items  = array();
        foreach ($this->ores as $ore) {
            $item = '\Botlife\Entity\Bar\Item\Ore\\' . $ore;
            $item = new $item;
            $items[$ore] = $item->mineChance;
        }
        $chance = array();
        $left   = 100;
        $amount = 0;
        foreach ($items as $state => $percentage) {
            if ($percentage == -1) {
                ++$amount;
            } else {
                $left -= $percentage;
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
        $ore = '\Botlife\Entity\Bar\Item\Ore\\' . $chance[mt_rand(0, 99)];
        return new $ore;
    }

}
