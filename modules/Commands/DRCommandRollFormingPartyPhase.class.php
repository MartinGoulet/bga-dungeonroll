<?php

class DRCommandRollFormingPartyPhase extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('postFormingParty');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $party_dice = DRPartyDice::getPartyDice($itemsInPlay);

        return sizeof($itemsInPlay) > 0 && sizeof($itemsInPlay) == sizeof($party_dice);
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $party_dice = DRPartyDice::getPartyDice($itemsInPlay);

        // Roll all party dice
        $rolledDice = DRItem::rollDice($party_dice);
        $rolledDice = DRItem::setZone($rolledDice, ZONE_PARTY);
        $this->game->notif->rerollPartyDice($party_dice, $rolledDice);
        $this->game->manager->updateItems($rolledDice);

        // Next state
        $this->game->gamestate->nextState();
    }
}
