<?php

namespace Botlife\Module;

class Bar extends AModule
{

    public $commands = array(
        '\Botlife\Command\Bar\Bar',
        '\Botlife\Command\Bar\PlayBar',
        '\Botlife\Command\Bar\Inv',
        '\Botlife\Command\Bar\Mine',
    );

}
