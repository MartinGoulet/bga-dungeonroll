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
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $hero = $this->game->components->getActivePlayerHero();
        return sizeof($itemsInPlay) == $hero->getNumberTreasureTokenToDiscard();
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $itemsInPlay = DRItem::setZone($itemsInPlay, ZONE_BOX);

        $this->game->manager->updateItems($itemsInPlay);
        $this->game->notif->discardTreasure($itemsInPlay);

        // Go to the next state
        $previous_state = $this->game->vars->getChooseDieState();
        $this->game->gamestate->nextState($previous_state);
    }
}