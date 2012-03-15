<?php

namespace Botlife\Module;

class Bar extends AModule
{

    public $commands = array(
        '\Botlife\Command\Bar\Bar',
        '\Botlife\Command\Bar\PlayBar',
        '\Botlife\Command\Bar\Inv',
        '\Botlife\Command\Bar\Mine',
        '\Botlife\Command\Bar\Smith',
        '\Botlife\Command\Bar\Admin\Give',
    );

}
