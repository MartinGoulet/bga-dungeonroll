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
        $this->applySpecialty($dice);
    }

    function actionAfterRollingDiceWithScroll($dice)
    {
        $this->applySpecialty($dice);
    }

    function applySpecialty($dice)
    {
        // All Chests become Potions
        $chests = DRItem::getSameAs($dice, DRDungeonDice::getDie(DR_DIE_CHEST));
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRDungeonDice::isChest', DR_DIE_POTION);
        if (sizeof($changes)) {
            $this->game->notif->changeChestToPotion($changes, $chests);
        };
    }


    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_GRAVEYARD);
        return sizeof($items) >= 1;
    }

    function executeUltimate($sub_command_id)
    {

        // Roll X Party dice from the Graveyard and add it to your Party
        $dice = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_GRAVEYARD);
        $dice = array_slice($dice, 0, $this->getNumberDiceFromGraveyard());
        $dice = DRItem::rollDice($dice);
        $dice = DRItem::setZone($dice, DR_ZONE_PLAY);

        $this->game->manager->updateItems($dice);

        $this->game->notif->ultimateAlchemist($dice);

        $this->game->gamestate->nextState('ultimate');
    }

    protected function getNumberDiceFromGraveyard()
    {
        return 1;
    }
}
