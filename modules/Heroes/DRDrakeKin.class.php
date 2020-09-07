<?php

class DRDrakeKin extends DRSorceress
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    /**
     * Game breaking rules
     */
    function canLevelUp()
    {
        return false;
    }

    function groupByDiceValue($dice) {
        $arr = array();

        foreach ($dice as $item) {
            $arr[$item['value']][] = $item;
        }

        return $arr;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        // For each die in the Dragon's Lair, discard 1 Monster
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $dragons = $this->game->components->getActivePlayerItemsByZone(ZONE_DRAGON_LAIR);
        
        $monstersByGroup = $this->groupByDiceValue($monsters);
        return sizeof($monstersByGroup) >= 1 && sizeof($monstersByGroup) <= sizeof($dragons);
    }

}
