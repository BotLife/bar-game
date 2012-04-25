<?php

namespace Botlife\Entity\Bar\Dao;

class ItemPrice
{
    
    public function getPrice($itemId)
    {
        $response = \DataGetter::getData(
        	'file-content', 'http://rscript.org/lookup.php?type=ge&search=' . $itemId
        );
        $response = explode("\n", $response);
        foreach ($response as &$data) {
            $data = explode(': ', $data);
            if ($data[0] == 'ITEM') {
                $data = explode(' ', $data[1]);
                $math = new \Botlife\Utility\Math;
                return $math->evaluate($data[2]);
            }
        }
    }
    
}