<?php

class DRCommandUsePotion extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        $items = $this->game->components->getActivePlayerUsableItems();
        $potions = DRUtils::filter($items, 'DRTreasureToken::isPotion');
        if(sizeof($potions) == 0) {
            return array();
        }
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;
        
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        // Get chests items
        $potionToken = DRUtils::filter($itemsInPlay, function($item) {
            return DRTreasureToken::isPotion($item);
        });
        $itemsInGraveyard = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_GRAVEYARD);
        return sizeof($potionToken) == 1 && sizeof($itemsInGraveyard) >= 1;
    }

    public function execute($sub_command_id)
    {
        // Get all items from play
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        // Get chests items
        $potionToken = DRUtils::filter($itemsInPlay, function($item) {
            return DRTreasureToken::isPotion($item);
        });

        // Return potions to the box
        $potionToken = DRItem::setZone($potionToken, DR_ZONE_BOX);

        // Update database
        $this->game->manager->updateItems($potionToken);
        // Notify for the move
        $this->game->notif->useElixir($potionToken);

        // Set the number of die to retrieve from the graveyard
        $this->game->vars->setChooseDieCount(1);
        $this->game->vars->setChooseDieState($this->getState());
        // Move to next state
        $this->game->gamestate->nextState('chooseDie');
    }

}
