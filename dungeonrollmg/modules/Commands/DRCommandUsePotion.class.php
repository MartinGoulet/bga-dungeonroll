<?php

class DRCommandUsePotion extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;
        
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        // Get chests items
        $potionToken = DRUtils::filter($itemsInPlay, function($item) {
            return DRTreasureToken::isPotion($item);
        });
        $itemsInGraveyard = $this->game->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD);
        return sizeof($potionToken) == 1 && sizeof($itemsInGraveyard) >= 1;
    }

    public function execute($sub_command_id)
    {
        // Get all items from play
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        // Get chests items
        $potionToken = DRUtils::filter($itemsInPlay, function($item) {
            return DRTreasureToken::isPotion($item);
        });

        // Return potions to the box
        $potionToken = DRItem::setZone($potionToken, ZONE_BOX);

        // Update database
        $this->game->manager->updateItems($potionToken);
        // Notify for the move
        $this->game->NTA_itemMove($potionToken);

        // Set the number of die to retrieve from the graveyard
        $this->game->vars->setChooseDieCount(1);
        $this->game->vars->setChooseDieState($this->getState());
        // Move to next state
        $this->game->gamestate->nextState('chooseDie');
    }

}
