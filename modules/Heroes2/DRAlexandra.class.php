<?php

class DRAlexandra extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    public function getUltimateAllowedStates()
    {
        return array('dragonPhase');
    }

    public function getSpecialtyAllowedStates()
    {             
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    function getSelectionDiceText() 
    {
        return clienttranslate("any number of Party and Dungeon dice");
    }

    function isSelectionDiceCorrect()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $dungeon = DRDungeonDice::getDungeonDice($items);
        $party = DRPartyDice::getPartyDice($items);
        $dragons = DRDungeonDice::getDragonDice($items);
        return (sizeof($dungeon) + sizeof($party)) == sizeof($items) &&
                sizeof($dragons) == 0; 
    }

    function rerollDice()
    {
        // Callback from specialty

        // Get all items from play
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        // Reroll other dice
        $rolledDice = DRItem::rollDice($items);

        // Move dice the the right zone
        $dragons = DRDungeonDice::getDragonDice($rolledDice);
        $dragons = DRItem::setZone($dragons, ZONE_DRAGON_LAIR);

        $dungeons = DRDungeonDice::getDungeonDiceWithoutDragon($rolledDice);
        $dungeons = DRItem::setZone($dungeons, ZONE_DUNGEON);

        $partys = DRPartyDice::getPartyDice($rolledDice);
        $partys = DRItem::setZone($partys, ZONE_PARTY);

        $rolledDice = array_merge($dragons, $dungeons, $partys);

        $this->game->manager->updateItems($rolledDice);
        $this->game->notif->ultimateTristan($rolledDice, $items);

        $this->game->gamestate->nextState();

    }
    
    /**
     * Game breaking rules
     */

    function canDefeatMonster()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $scrolls = DRUtils::filter($itemsInPlay, function ($item) {
            return DRPartyDice::isScroll($item) || DRTreasureToken::isScroll($item);
        });
        $monstersTypeCount = DRDungeonDice::getMonsterTypeCount($itemsInPlay);
        $companions = DRItem::getCompanions($itemsInPlay);

        return $monstersTypeCount == 1 && sizeof($scrolls) == 1 && sizeof($companions) == 0;
    }

    function canDefeatDragon()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        $scrolls = DRUtils::filter($itemsInPlay, function ($item) {
            return DRPartyDice::isScroll($item) || DRTreasureToken::isScroll($item);
        });
        
        $companions = DRItem::getCompanions($itemsInPlay);
        $champions = DRUtils::filter($itemsInPlay, 'DRPartyDice::isChampion');
        $distinctType = array_unique(DRItem::getCompanionTypes($companions));

        return sizeof($companions) + sizeof($scrolls) == 3 &&
               sizeof($scrolls) + sizeof($champions) <= 1 &&
               sizeof($distinctType) == sizeof($companions);
    }

    function canOpenAllChests()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $scrolls = DRUtils::filter($itemsInPlay, function ($item) {
            return DRPartyDice::isScroll($item) || DRTreasureToken::isScroll($item);
        });
        $chests  = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isChest');

        return sizeof($scrolls) + sizeof($chests) == sizeof($itemsInPlay) &&
               sizeof($scrolls) == 1 &&
               sizeof($chests) >= 1;
    }

    function canExecuteSpecialty()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $party = DRPartyDice::getPartyDice($itemsInPlay);
        return sizeof($party) == 1 && sizeof($itemsInPlay) == 1;
    }

    function executeSpecialty()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $items = DRItem::setZone($items, ZONE_GRAVEYARD);
        $this->game->manager->updateItems($items);
        $this->game->NTA_itemMove($items);
        $this->game->gamestate->nextState('alexandra');
    }
    
    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $dragons = DRDungeonDice::getDragonDice($items);
        $scrolls = DRUtils::filter($items, function($item) {
            return DRItem::isScroll($item);
        });

        return sizeof($scrolls) == 1 && 
               (sizeof($scrolls) + sizeof($dragons)) == sizeof($items);
    }

    function executeUltimate($sub_command_id)
    {
        $command = $this->game->commands->getCommandByName('fightDragon');
        $command->execute(0);
    }
}
