<?php

class DRCommandFightDragon extends DRCommand
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

        // Get all items from play
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $hero = $this->game->components->getActivePlayerHero();

        if ($hero->canDefeatDragon()) {
            return true;
        }

        // Get companions in play
        $companions = $this->game->components->getActivePlayerCompanionsByZone(ZONE_PLAY);
        $dragons = DRDungeonDice::getDragonDice($items);

        // If there is more than companions and dragons in play
        if (sizeof($items) != (sizeof($companions) + sizeof($dragons))) {
            return false;
        }

        // There must be 3 companions in play
        if (sizeof($companions) < $hero->getCompanionCountDefeatDragon()) {
            return false;
        } else if (sizeof($companions) > $hero->getCompanionCountDefeatDragon()) {
            return false;
        }

        // All companion must be of different type
        $typeCompanions = DRItem::getCompanionTypes($companions);
        $distinctType = array_unique($typeCompanions);

        // If all companions is not of different type
        if(sizeof($distinctType) != $hero->getCompanionCountDefeatDragon()) {
            return false;
        }

        // The fight is legit
        return true;
    }

    public function execute($sub_command_id)
    {
        // Find all items in play
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        // Separate items by type
        $dragons = DRDungeonDice::getDragonDice($itemsInPlay);
        $party = DRPartyDice::getPartyDice($itemsInPlay);
        $tokens = DRTreasureToken::getTreasureTokens($itemsInPlay);

        // Set the zone for each type of item
        $dragons = DRItem::setZone($dragons, ZONE_BOX);
        $party = DRItem::setZone($party, ZONE_GRAVEYARD);
        $tokens = DRItem::setZone($tokens, ZONE_BOX);

        // Get a treasure from the dragon
        $player_id = $this->game->getActivePlayerId();
        $treasures = $this->game->components->getItemsByTypeAndZone(TYPE_TREASURE_TOKEN, ZONE_BOX);
        $treasures = DRUtils::random_item($treasures, 1);
        $treasures = DRItem::setOwner($treasures, $player_id);
        $treasures = DRItem::setZone($treasures, ZONE_INVENTORY);

        // Concat all items into a single array
        $itemsInPlay = array_merge($dragons, $party, $tokens, $treasures);

        // Update items and notify for items moves.
        $this->game->manager->updateItems($itemsInPlay);
        $this->game->NTA_itemMove($itemsInPlay);

        // Stats
        $this->game->stats->incDragonKilled(sizeof($dragons));
        
        $this->game->incPlayerScore(1);
        $this->game->notif->updatedScores();

        // Next state
        $this->game->gamestate->nextState('killDragons');
    }
}
