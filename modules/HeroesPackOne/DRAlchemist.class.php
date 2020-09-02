<?php

class DRAlchemist extends DRStandardHero
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
        // When rolling Dungeon dice, all Chests become Potions
        $changes = array();
        // &$die : Alter the die in parameter
        foreach ($dice as &$die) {
            if (DRDungeonDice::isChest($die)) {
                $die['value'] = DIE_POTION;
                $changes[] = $die;
            }
        }

        // Notify the modification
        if (sizeof($changes) > 0) {
            $this->game->manager->updateItems($dice);
            $this->game->notif->changeChestToPotion($changes);
        }
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate() 
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD);
        return sizeof($items) >= 1;
    }

    function executeUltimate($sub_command_id)
    {

        // Roll X Party dice from the Graveyard and add it to your Party
        $dice = $this->game->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD);
        $dice = array_slice($dice, 0, $this->getNumberDiceFromGraveyard());
        $dice = DRItem::rollDice($dice);
        $dice = DRItem::setZone($dice, ZONE_PLAY);

        $this->game->manager->updateItems($dice);
        
        $this->game->NTA_itemMove($dice);
        $this->game->notif->updatePossibleActions();

    }

    protected function getNumberDiceFromGraveyard() {
        return 1;
    }
}
