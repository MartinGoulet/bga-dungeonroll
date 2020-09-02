<?php

class DRThaumaturge extends DRAlchemist
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

    protected function getNumberDiceFromGraveyard() {
        return 2;
    }

}
