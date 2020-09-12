<?php

class DRRanger extends DRTracker
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

    function canExecuteUltimate()
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $monsters = DRDungeonDice::getMonsterDices($items);
        return sizeof($monsters) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $items = array_merge(
            $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY),
            $this->game->components->getActivePlayerItemsByZone(ZONE_DUNGEON)
        );

        $monsters = array_merge(
            $this->getOneMonster($items, 'DRDungeonDice::isGoblin'),
            $this->getOneMonster($items, 'DRDungeonDice::isOoze'),
            $this->getOneMonster($items, 'DRDungeonDice::isSkeleton')
        );

        // Return monsters to the box and notify for the kill
        $monsters = DRItem::setZone($monsters, ZONE_BOX);
        $this->game->manager->updateItems($monsters);
        $this->game->notif->ultimateTracker($monsters);

        $this->game->gamestate->nextState('ultimate');
    }

}
