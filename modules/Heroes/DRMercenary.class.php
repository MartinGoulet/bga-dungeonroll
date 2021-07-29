<?php

class DRMercenary extends DRStandardHero
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
        return true;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate() 
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        return sizeof($monsters) >= 1 && sizeof($monsters) <= 2;
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);

        // Return monsters to the box and notify for the kill
        $monsters = DRItem::setZone($monsters, DR_ZONE_BOX);
        $this->game->manager->updateItems($monsters);
        $this->game->notif->ultimateMercenary($monsters);

        $this->game->gamestate->nextState('fight');
    }
}
