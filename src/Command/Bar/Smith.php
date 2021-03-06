<?php

namespace Botlife\Command\Bar;

class Smith extends \Botlife\Command\ACommand
{

    public $regex     = array(
        '/^[.!]smith( )?(?P<type>.+)?$/i',
    );
    public $action    = 'run';
    public $code      = 'smith';
    
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
        if (!isset($event->matches['type'])) {
            $this->respondWithPrefix(
                'Smithing is nice isn\'t it? But before you can smith you need '
                    . 'to specify which bar you want. Example: '
                    . '!smith bronze'
            );
            return;
        }
        $barType = \Botlife\Entity\Bar\ItemDb::getItem(
            $event->matches['type'], new \Botlife\Entity\Bar\Item\Bar
        );
        if (!$barType) {
            $this->respondWithPrefix(
                'Damn I don\'t know that kind of bar... Did you mean one of '
                    . 'the following: bronze bar, gold bar or rune bar?'
            );
            return;
        }
        $user = $bar->users->{strtolower($event->auth)};
        $this->smith($user, $barType);
        \Botlife\Application\Storage::saveData('bar', $bar);
    }
    
    public function smith(&$user, $bar)
    {
        $c        = new \Botlife\Application\Colors;
        $metDeps  = true;
        $barsMade = 0;
        while ($metDeps) {
            foreach ($bar->smithDeps as $item => $amount) {
                $ore = '\Botlife\Entity\Bar\Item\Ore\\' . $item;
                $ore = new $ore;
                if (!$user->inventory->hasItemAmount($ore, $amount)) {
                    $metDeps = false;
                }
            }
            if ($metDeps) {
                $user->inventory->incItemAmount($bar);
                foreach ($bar->smithDeps as $item => $amount) {
                    $ore = '\Botlife\Entity\Bar\Item\Ore\\' . $item;
                    $ore = new $ore;
                    $user->inventory->decItemAmount($ore, $amount);
                }
                ++$barsMade;
            }
        }
        if (!$barsMade) {
            $deps = array();
            foreach ($bar->smithDeps as $item => $amount) {
                $ore = '\Botlife\Entity\Bar\Item\Ore\\' . $item;
                $ore = new $ore;
                $deps[] = $c(3, $ore->getName($amount)) . $c(12, ' = ')
                    . $c(3, $amount);
            }
            $this->respondWithPrefix(sprintf(
                'You need the following resources to make a ' . $c(3, '%s') 
                    . $c(12, ': ') . $c(3, '%s'),
                $bar->name, implode($c(12, ', '), $deps)
            ));
            return;
        }
        if (mt_rand(1, 50) == 40) {
            $this->respondWithPrefix(sprintf(
                'While waiting for the furnace to finish you see a little chest '
                	. 'laying on the ground. You picked it up. You can open it '
            		. 'with !openchest'
            ));
            $chest = \Botlife\Entity\Bar\ItemDb::getItem('chest');
            $user->inventory->incItemAmount($chest);
        }
        $this->respondWithPrefix(sprintf(
            'You just made ' . $c(3, '%s %s') . $c(12, '! You now have ')
                . $c(3, '%s %s') . $c(12, '.'),
            number_format($barsMade), $bar->getName($barsMade),
            number_format($user->inventory->getItemAmount($bar)),
            $bar->getName($barsMade)
        ));
    }

}
