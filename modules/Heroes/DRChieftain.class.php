<?php

class DRChieftain extends DRHalfGoblin
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }


    /**
     * Game breaking rules
     */
    function canLevelUp()
    {
        return false;
    }

    protected function getNumberGoblinsToThief() {
        return 2;
    }

}
