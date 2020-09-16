<?php

class DRViking extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }
    
    /**
     * Game breaking rules
     */
    function getTotalPartyDice() {
        return 5;
    }

    function canLevelUp()
    {
        return true;
    }

    /**
     * States
     */

    function stateBeforeFormingParty(&$dice)
    {
        // Take 5 Champions instead of rolling
        for ($i=0; $i < 5; $i++) { 
            $dice[$i]['value'] = DIE_CHAMPION;
        }
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate() 
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $dragons = DRDungeonDice::getDragonDice($items);
        return sizeof($dragons) > 0;
    }

    function executeUltimate($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $dragons = DRDungeonDice::getDragonDice($items);
        $dragons = DRItem::setZone($dragons, ZONE_BOX);
        $this->game->manager->updateItems($dragons);
        $this->game->notif->ultimateDiscardDragon($dragons);

        $this->game->gamestate->nextState('ultimate');

    }
}
