#!/usr/bin/env php
<?php

ob_start();

defined('APPLICATION_PATH') || define(
    'APPLICATION_PATH',
    $_SERVER['argv'][2]
);

set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(APPLICATION_PATH . '/library'),
            realpath(APPLICATION_PATH . '/../../src'),
            APPLICATION_PATH,
            get_include_path(),
        )
    )
);

require_once 'Application.php';
require_once 'Application/Autoloader.php';
require_once 'IRCBot/src/shortFunctions.php';

$application = new \BotLife\Application();
$application->bootstrap();

$items       = array(
    /* Bars */
    '\Botlife\Entity\Bar\Item\Bar\BronzeBar',
    '\Botlife\Entity\Bar\Item\Bar\RuneBar',
    '\Botlife\Entity\Bar\Item\Bar\GoldBar',
    /* Pickaxes */
    '\Botlife\Entity\Bar\Item\Pickaxe\BronzePickaxe',
    '\Botlife\Entity\Bar\Item\Pickaxe\RunePickaxe',
    /* Ores */
    '\Botlife\Entity\Bar\Item\Ore\Tin',
    '\Botlife\Entity\Bar\Item\Ore\Copper',
    '\Botlife\Entity\Bar\Item\Ore\Coal',
    '\Botlife\Entity\Bar\Item\Ore\GoldOre',
    '\Botlife\Entity\Bar\Item\Ore\RuneOre',
);

$tmp = array();
foreach ($items as $item) {
    $item = new $item;
    if ($item instanceof \Botlife\Entity\Bar\Item\Bar) {
        $tmp['Bars'][0] = array('Item ID', 'Bar type');
        $tmp['Bars'][] = array($item->id, $item->name);
    } elseif ($item instanceof \Botlife\Entity\Bar\Item\Pickaxe) {
        $tmp['Pickaxes'][0] = array('Item ID', 'Type', 'Quality');
        $tmp['Pickaxes'][] = array($item->id, $item->name, $item->quality);
    } elseif ($item instanceof \Botlife\Entity\Bar\Item\Ore) {
        $tmp['Ores'][0] = array('Item ID', 'Ore', 'Quality', 'Mining chance');
        $mineChance = ($item->mineChance != -1) ? $item->mineChance : 'Auto';
        $tmp['Ores'][] = array(
            $item->id, $item->name, $item->quality, $mineChance
        );
    }
}

$output = null;

foreach ($tmp as $type => $table) {
    $output .= '### ' . $type . PHP_EOL . PHP_EOL . '```' . PHP_EOL;
    foreach ($table as $rowId => $row) {
        if ($rowId == 0) {
            $output .= '|' . str_repeat(
                '|' . str_repeat('-', 17) . '|', count($row)
            ) . '|' . PHP_EOL;
        }
        $output .= '|';
        foreach ($row as $value) {
            $output .= '| ' . str_pad($value, 16) . '|';
            
        }
        $output .= '|';
        if ($rowId == 0) {
            $output .= PHP_EOL . '|' . str_repeat(
                '|' . str_repeat('-', 17) . '|', count($row)
            ) . '|';
        }
        $output .= PHP_EOL;
    }
    $output .= '|' . str_repeat(
        '|' . str_repeat('-', 17) . '|', count($row)
    ) . '|' . PHP_EOL;
    $output .='```' . PHP_EOL . PHP_EOL;
} 

ob_clean();

echo 'Writing output to ' . $_SERVER['argv'][1] . '.' . PHP_EOL;
file_put_contents($_SERVER['argv'][1], $output);

