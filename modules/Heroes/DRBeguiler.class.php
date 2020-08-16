<?php

class DRBeguiler extends DREnchantress
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    protected function getNumberOfMonsterToTransform()
    {
        return 2;
    }
    
    /**
     * Game breaking rules
     */
    function canLevelUp()
    {
        return false;
    }

}
