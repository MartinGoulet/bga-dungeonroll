<?php

class DRSorceress extends DRStandardHero
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
     * States
     */
    function stateAfterDungeonDiceRoll($dice)
    {
        $this->applySpecialty();
    }

    function actionAfterRollingDiceWithScroll($dice) 
    {
        $this->applySpecialty();
    }

    function afterDragonBait() 
    {
        $this->applySpecialty();
    }

    function applySpecialty() 
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $dragons = DRDungeonDice::getDragonDice($items);
        if(sizeof($dragons) >= 3) {
            $dragons = DRItem::setZone($dragons, ZONE_BOX);
            $this->game->manager->updateItems($dragons);
            $this->game->notif->sorceressDiscardDragon($dragons);
        }
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        // For each die in the Dragon's Lair, discard 1 Monster
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $dragons = $this->game->components->getActivePlayerItemsByZone(ZONE_DRAGON_LAIR);
        return sizeof($monsters) >= 1 && sizeof($monsters) <= sizeof($dragons);
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);

        $monsters = DRItem::setZone($monsters, ZONE_BOX);

        $this->game->manager->updateItems($monsters);
        $this->game->notif->ultimateSorceress($monsters);

        $this->game->gamestate->nextState('ultimate');
    }
}
