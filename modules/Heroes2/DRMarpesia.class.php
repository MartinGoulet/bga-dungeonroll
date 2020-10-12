<?php

class DRMarpesia extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    public function getUltimateAllowedStates()
    {
        return array('monsterPhase', 'lootPhase', 'dragonPhase', 'regroupPhase');
    }

    function getNumberOfDragonRequiredForDragonPhase()
    {
        return 4;
    }

    function isChampionKillMultipleMonsters()
    {
        return false;
    }

    function isChampionOpenMultipleChests()
    {
        return false;
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

        // Roll all dice from the Graveyard, discard any scrolls and add the rest to your Party
        $dice = $this->game->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD);
        $dice = DRItem::rollDice($dice);
        $dice = DRItem::setZone($dice, ZONE_PLAY);
        $this->game->manager->updateItems($dice);
        $this->game->notif->rerollDiceWithoutMessage($dice);

        $dice = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $scrolls = DRPartyDice::getScrollDice($dice);
        $companions = DRPartyDice::getCompanionDice($dice);

        $scrolls = DRItem::setZone($scrolls, ZONE_GRAVEYARD);
        $companions = DRItem::setZone($companions, ZONE_PARTY);

        $dice = array_merge($scrolls, $companions);
        $this->game->manager->updateItems($dice);

        $this->game->notif->ultimateMarpesia($dice, $scrolls);

        $this->game->gamestate->nextState('ultimate');
    }
}
