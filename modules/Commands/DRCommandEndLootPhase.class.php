<?php

class DRCommandEndLootPhase extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('lootPhase');
    }

    public function execute($sub_command_id)
    {
        // Get all dungeon dice in play except dragon
        $dice = $this->game->components->getActivePlayerUsableItems();
        $dungeon_dice = DRDungeonDice::getDungeonDiceWithoutDragon($dice);
        // Move dungeon dice outside the game
        $dungeon_dice = DRItem::setZone($dungeon_dice, DR_ZONE_BOX);
        // Notify all players for the move
        $this->game->manager->updateItems($dungeon_dice);
        $this->game->NTA_itemMove($dungeon_dice);
        // Next state
        $this->game->gamestate->nextState('end');
    }
}
