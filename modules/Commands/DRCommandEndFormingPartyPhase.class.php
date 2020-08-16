<?php

class DRCommandEndFormingPartyPhase extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('postFormingParty');
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        if (sizeof($itemsInPlay) > 0) {
            // Separate items
            $dice = DRPartyDice::getPartyDice($itemsInPlay);
            $treasures = DRTreasureToken::getTreasureTokens($itemsInPlay);
            // Move items to the right zone
            $dice = DRItem::setZone($dice, ZONE_PARTY);
            $treasures = DRItem::setZone($treasures, ZONE_INVENTORY);
            // Notify all players for the move
            $itemsUpdate = array_merge($dice, $treasures);
            $this->game->manager->updateItems($itemsUpdate);
            $this->game->NTA_itemMove($itemsUpdate);
        }

        // Go to the next state
        $this->game->gamestate->nextState();
    }
}
