<?php

class DRCommandOpenChest extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        $allowedStates = array('lootPhase');

        $hero = $this->game->components->getActivePlayerHero();
        if ($hero instanceof DRHalfGoblin) {
            $allowedStates[] = 'monsterPhase';
        }

        return $allowedStates;
    }

    public function canExecute()
    {
        if (!parent::canExecute()) {
            return false;
        }

        $hero = $this->game->components->getActivePlayerHero();
        if($hero->canOpenAllChests()) {
            return true;
        }

        // Get all items from play
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        // Get chests items
        $chests = DRItem::getSameAs($items, DRDungeonDice::getDie(DR_DIE_CHEST));
        // Get companions
        $companions = DRItem::getCompanions($items);

        if (sizeof($chests) == 0) {
            return false;
        } else if (sizeof($items) != (sizeof($chests) + sizeof($companions))) {
            return false;
        } else if (sizeof($companions) > sizeof($chests)) {
            return false;
        }

        // If there are more chest than companions
        if (sizeof($companions) < sizeof($chests)) {

            $partyCanOpenAllChests = array_values(array_filter($items, function ($item) use($hero) {
                return DRItem::isThief($item) || 
                (
                    DRPartyDice::isChampion($item) && $hero->isChampionOpenMultipleChests()
                );
            }));

            // If only 1 companion found, check if he can open all chest
            if (sizeof($companions) == 1 && sizeof($partyCanOpenAllChests) == 1) {
                return true;
            } else if (sizeof($companions) > 1 && sizeof($partyCanOpenAllChests) >= 1) {
                return false;
            } else {
                return false;
            }
        }

        return true;
    }

    public function execute($sub_command_id)
    {
        // Get all items from play
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        
        $dungeonDice = DRItem::getSameAs($items, DRDungeonDice::getDie(DR_DIE_CHEST));
        $partyDice   = DRPartyDice::getPartyDice($items);
        $partyToken  = DRTreasureToken::getTreasureTokens($items);

        $dungeonDice = DRItem::setZone($dungeonDice, DR_ZONE_BOX);
        $partyToken  = DRItem::setZone($partyToken, DR_ZONE_BOX);
        $partyDice   = DRItem::setZone($partyDice, DR_ZONE_GRAVEYARD);

        $player_id = $this->game->getActivePlayerId();

        $treasures = $this->game->components->getItemsByTypeAndZone(DR_TYPE_TREASURE_TOKEN, DR_ZONE_BOX);
        $treasures = DRUtils::random_item($treasures, sizeof($dungeonDice));
        $treasures = DRItem::setOwner($treasures, $player_id);
        $treasures = DRItem::setZone($treasures, DR_ZONE_INVENTORY);

        for ($i = 0; $i < sizeof($treasures); $i++) {
            $treasures[$i]['from'] = $dungeonDice[$i]['id'];
            $treasures[$i]['previous_zone'] = DR_ZONE_PLAY;
        }

        $this->game->stats->incTreasureOpen(sizeof($treasures));

        $partyUpdate = array_merge($partyDice, $partyToken);
        
        $this->game->manager->updateItems($dungeonDice);
        $this->game->manager->updateItems($treasures);
        $this->game->manager->updateItems($partyUpdate);

        $this->game->NTA_itemMove($partyUpdate);
        $this->game->notif->openChest(array_merge($partyDice, $partyToken), $dungeonDice, $treasures);

        $hero = $this->game->components->getActivePlayerHero();
        $hero->afterOpenChest();

        $this->game->gamestate->nextState('chest');
    }
}
