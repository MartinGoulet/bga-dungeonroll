<?php

class DRTombRaider extends DRArchaeologist
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

    function getNumberTreasureTokenToDiscard()
    {
        if($this->game->vars->getChooseDieState() == "nextPlayer") {
            // At game end, he must discard 6 treasures;
            return 6;
        } else {
            return 1;
        }
    }

}
