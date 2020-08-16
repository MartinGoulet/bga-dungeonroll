<?php

class DRDragonSlayer extends DRKnight
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

    function getCompanionCountDefeatDragon()
    {
        return 2;
    }

}