<?php

class DRCommander extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    function isStateOk()
    {
        $allowedStates = array('monsterPhase', 'lootPhase');
        return in_array($this->getState(), $allowedStates);
    }

    /**
     * Game breaking rules
     */
    function canDefeatMonster()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $fighters = DRUtils::filter($itemsInPlay, 'DRItem::isFighter');
        if (sizeof($fighters) != 1) {
            return false;
        }

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
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

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $dice = DRUtils::filter($itemsInPlay, function ($item) {
            return DRItem::isPartyDie($item) || DRItem::isDungeonDie($item);
        });
        return sizeof($dice) >= 1 && sizeof($itemsInPlay) == sizeof($dice);
    }

    function executeUltimate($sub_command_id)
    {
        // Get all items from play
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        // Reroll other dice
        $rolledDice = DRItem::rollDice($items);

        // Move dice the the right zone
        $dragons = DRDungeonDice::getDragonDice($rolledDice);
        $dragons = DRItem::setZone($dragons, ZONE_DRAGON_LAIR);

        $partys = DRPartyDice::getPartyDice($rolledDice);
        $partys = DRItem::setZone($partys, ZONE_PARTY);

        $dungeons = DRDungeonDice::getDungeonDiceWithoutDragon($rolledDice);
        $dungeons = DRItem::setZone($dungeons, ZONE_DUNGEON);

        $rolledDice = array_merge($dragons, $partys, $dungeons);

        $this->game->manager->updateItems($rolledDice);
        $this->game->notif->ultimateCommander($rolledDice);
        $this->game->notif->updatePossibleActions();
    }
}
