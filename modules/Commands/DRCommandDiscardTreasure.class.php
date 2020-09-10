<?php

class DRCommandDiscardTreasure extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('discardTreasure');
    }

    public function canExecute()
    {
        $itemsInPlay = $this->getTokensInPlay();
        $itemsInInventory = $this->game->components->getActivePlayerItemsByZone(ZONE_INVENTORY);
        $hero = $this->game->components->getActivePlayerHero();
        return sizeof($itemsInPlay) == $hero->getNumberTreasureTokenToDiscard() ||
               sizeof($itemsInInventory) == 0;
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->getTokensInPlay();
        $itemsInPlay = DRItem::setZone($itemsInPlay, ZONE_BOX);

        $this->game->manager->updateItems($itemsInPlay);
        $this->game->notif->discardTreasure($itemsInPlay);

        // Go to the next state
        $previous_state = $this->game->vars->getChooseDieState();
        $this->game->gamestate->nextState($previous_state);
    }

    public function getTokensInPlay()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $itemsInPlay = DRUtils::filter($itemsInPlay, function($item) {
            return DRItem::isTreasureToken($item);
        });
        return $itemsInPlay;
    }
}
