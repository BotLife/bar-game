<?php

namespace Botlife\Entity\Bar\Item;

class AItem
{

    public $id;
    public $name;
    public $pluralName;
    
    public $quality = 1;
    
    public function getName($amount)
    {
        if (!$this->pluralName) {
            $this->pluralName = $this->name;
        }
        if ($amount == 1) {
            return $this->name;
        }
        return $this->pluralName;
    }
    
}
