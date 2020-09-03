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
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRDungeonDice::isChest', DIE_POTION);
        if (sizeof($changes)) {
            $this->game->notif->changeChestToPotion($changes);
        };
    }

    function actionAfterRollingDiceWithScroll($dice) 
    {
        // All Skeletons become Potions
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRDungeonDice::isChest', DIE_POTION);
        if (sizeof($changes)) {
            $this->game->notif->changeChestToPotion($changes);
        };
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

    protected function getNumberDiceFromGraveyard()
    {
        return 1;
    }
}
