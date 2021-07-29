<?php

class DRcommandSelectScrollPartyPhase extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates()
    {
        $hero = $this->game->components->getActivePlayerHero();
        if ($hero instanceof DRLoegYllavyre) {
            return array('postFormingPartyLeogYllavyre');
        } else {
            return array();
        }
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $party_dice = DRPartyDice::getPartyDice($itemsInPlay);

        return sizeof($itemsInPlay) == 2 && sizeof($itemsInPlay) == sizeof($party_dice);
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $party_dice = DRPartyDice::getPartyDice($itemsInPlay);

        // Set 2 dice to scrolls.
        $party_dice[0]['value'] = DR_DIE_SCROLL;
        $party_dice[1]['value'] = DR_DIE_SCROLL;

        $party_dice = DRItem::setZone($party_dice, DR_ZONE_PARTY);
        $this->game->notif->heroTransformDice($itemsInPlay, $party_dice);
        $this->game->manager->updateItems($party_dice);

        // Next state
        $this->game->gamestate->nextState();
    }
}
