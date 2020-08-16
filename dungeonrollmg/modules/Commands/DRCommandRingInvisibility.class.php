<?php

class DRCommandRingInvisibility extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('dragonPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;
        
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        return in_array($this->getState(), array('dragonPhase')) &&
            DRItem::containsSameAs($items, DRTreasureToken::getToken(TOKEN_RING_INVISIBILITY));
    }

    public function execute($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $rings = DRItem::getSameAs($items, DRTreasureToken::getToken(TOKEN_RING_INVISIBILITY));

        // Select all dragons
        $dragons = DRItem::getSameAs($items, DRDungeonDice::getDie(DIE_DRAGON));

        // Discard the ring and all dragons
        $itemsUpdate = array_merge(
            DRItem::setZone(array($rings[0]), ZONE_BOX),
            DRItem::setZone($dragons, ZONE_BOX)
        );

        $this->game->manager->updateItems($itemsUpdate);
        $this->game->NTA_itemMove($itemsUpdate);

        // Go to the next state
        $this->game->gamestate->nextState('ringInvisibility');
    }
}
