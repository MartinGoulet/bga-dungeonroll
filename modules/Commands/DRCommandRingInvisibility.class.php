<?php

class DRCommandRingInvisibility extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        $items = $this->game->components->getActivePlayerUsableItems();
        $rings = DRUtils::filter($items, 'DRTreasureToken::isRingOfInvisibility');
        if(sizeof($rings) == 0) {
            return array();
        }
        return array('dragonPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;
        
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        return in_array($this->getState(), array('dragonPhase')) &&
            DRItem::containsSameAs($items, DRTreasureToken::getToken(DR_TOKEN_RING_INVISIBILITY));
    }

    public function execute($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $rings = DRItem::getSameAs($items, DRTreasureToken::getToken(DR_TOKEN_RING_INVISIBILITY));

        // Select all dragons
        $dragons = DRItem::getSameAs($items, DRDungeonDice::getDie(DR_DIE_DRAGON));

        // Discard the ring and all dragons
        $itemsUpdate = array_merge(
            DRItem::setZone(array($rings[0]), DR_ZONE_BOX),
            DRItem::setZone($dragons, DR_ZONE_BOX)
        );

        $this->game->manager->updateItems($itemsUpdate);
        $this->game->NTA_itemMove($itemsUpdate);

        $this->game->notif->useRingInvisibility();
        
        // Go to the next state
        $this->game->gamestate->nextState('ringInvisibility');
    }
}
