<?php

class DRCommander extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    function isStateOk()
    {
        $allowedStates = array('monsterPhase', 'lootPhase', 'dragonPhase');
        return in_array($this->getState(), $allowedStates);
    }

    /**
     * Game breaking rules
     */
    function canDefeatMonster()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
                
        $fighters = DRUtils::filter($itemsInPlay, 'DRItem::isFighter');
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);

        if (sizeof($fighters) + sizeof($monsters) !== sizeof($itemsInPlay)) {
            return false;
        }

        if (sizeof($fighters) != 1) {
            return false;
        }

        if (sizeof($monsters) <= 2)
            return true;

        $goblins = DRUtils::filter($monsters, 'DRDungeonDice::isGoblin');

        // Since fighter can win against goblins, he can defeat 1 additionnal monster
        if (sizeof($monsters) - sizeof($goblins) == 1) {
            return true;
        }

        return false;
    }


    /**
     * Game breaking rules
     */
    function canLevelUp()
    {
        return false;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        if (!$this->isStateOk())
            return false;

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $dice = DRUtils::filter($itemsInPlay, function ($item) {
            return DRItem::isPartyDie($item) || DRItem::isDungeonDie($item);
        });
        return sizeof($dice) >= 1 && sizeof($itemsInPlay) == sizeof($dice);
    }

    function executeUltimate($sub_command_id)
    {
        // Get all items from play (except dragons)
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $items = DRUtils::filter($items, function($item) { 
            return !DRDungeonDice::isDragon($item);
        });

        // Reroll other dice
        $rolledDice = DRItem::rollDice($items);

        // Move dice the the right zone
        $dragons = DRDungeonDice::getDragonDice($rolledDice);
        $dragons = DRItem::setZone($dragons, DR_ZONE_DRAGON_LAIR);

        $partys = DRPartyDice::getPartyDice($rolledDice);
        $partys = DRItem::setZone($partys, DR_ZONE_PARTY);

        $dungeons = DRDungeonDice::getDungeonDiceWithoutDragon($rolledDice);
        $dungeons = DRItem::setZone($dungeons, DR_ZONE_DUNGEON);

        $rolledDice = array_merge($dragons, $partys, $dungeons);

        $this->game->manager->updateItems($rolledDice);
        $this->game->notif->ultimateCommander($rolledDice, $items);
        
        // His ultimate is considered to be a scroll (and can move back to monster phase)
        $this->game->gamestate->nextState('scroll');
    }
}
