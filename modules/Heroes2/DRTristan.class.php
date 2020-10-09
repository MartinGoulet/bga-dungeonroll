<?php

class DRTristan extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    public function getUltimateAllowedStates()
    {
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    public function getSpecialtyAllowedStates()
    {
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    function afterDefeatMonster($party, $monsters)
    {
        // When defeating 2 or more monsters with 1 companions, draw 1 treasure
        if (sizeof($party) == 1 && sizeof($monsters) >= 2) {
            $treasures = $this->drawTreasures(1);
            $this->game->notif->heroDrawTreasure($treasures);
        }
    }

    function rerollDice()
    {
        // Callback from ultimate

        // Get all items from play
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        // Reroll other dice
        $rolledDice = DRItem::rollDice($items);

        // Move dice the the right zone
        $dragons = DRDungeonDice::getDragonDice($rolledDice);
        $dragons = DRItem::setZone($dragons, ZONE_DRAGON_LAIR);

        $dungeons = DRDungeonDice::getDungeonDiceWithoutDragon($rolledDice);
        $dungeons = DRItem::setZone($dungeons, ZONE_DUNGEON);

        $rolledDice = array_merge($dragons, $dungeons);

        $this->game->manager->updateItems($rolledDice);
        $this->game->notif->ultimateTristan($rolledDice, $items);

        $this->game->gamestate->nextState();

    }

    function isSelectionDiceCorrect()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $dungeon = DRDungeonDice::getDungeonDice($items);
        return sizeof($dungeon) == sizeof($items) &&
               sizeof($dungeon) >= 1;
    }

    function getSelectionDiceText() 
    {
        return clienttranslate("any number of dungeon dice (including Dragon dice)");
    }


    function canExecuteSpecialty()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $treasures = DRTreasureToken::getTreasureTokens($itemsInPlay);
        return sizeof($treasures) == 1 && $this->game->vars->getIsHeroActivated();
    }

    function executeSpecialty()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $treasures = DRTreasureToken::getTreasureTokens($itemsInPlay);
        $treasures = DRItem::setZone($treasures, ZONE_BOX);
        $this->game->manager->updateItems($treasures);
        $this->game->NTA_itemMove($treasures);

        $this->game->vars->setIsHeroActivated(false);
        $this->game->notif->refreshTristan($treasures);
    }

    /**
     * Must Overrides
     */

    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerUsableItems();
        $dungeon = DRDungeonDice::getDungeonDice($itemsInPlay);
        return sizeof($dungeon) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $this->game->gamestate->nextState('tristan');
    }
    
}
