<?php

class DRNecromancer extends DROccultist
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

    protected function getNumberSkeletonToFighter() {
        return 2;
    }

}
