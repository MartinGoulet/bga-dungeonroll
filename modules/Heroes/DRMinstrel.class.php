<?php

class DRMinstrel extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }
    
    /**
     * Game breaking rules
     */
    function canDefeatMonster()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $thieves = DRUtils::filter($itemsInPlay, 'DRItem::isThief');

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $oozes = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isOoze');

        // Thieves may be used as Mages and Mages may be used as Thieves.
        if (sizeof($thieves) == 1 && sizeof($oozes) == sizeof($monsters))
            return sizeof($thieves) + sizeof($oozes) == sizeof($itemsInPlay);

        return false;
    }

    function canOpenAllChests()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $mages = DRUtils::filter($itemsInPlay, 'DRItem::isMage');
        $chests = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isChest');

        // Thieves may be used as Mages and Mages may be used as Thieves.
        return sizeof($mages) + sizeof($chests) == sizeof($itemsInPlay) &&
               sizeof($mages) == 1 &&
               sizeof($chests) >= 1;
    }

    function canDefeatDragon()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        $companions = DRItem::getCompanions($itemsInPlay);
        $thieves = DRUtils::filter($companions, 'DRItem::isThief');
        $mages = DRUtils::filter($companions, 'DRItem::isMage');

        if (sizeof($companions) == 3) {
            if (sizeof($thieves) == 2 && sizeof($mages) == 0)
                return true;
            elseif (sizeof($thieves) == 0 && sizeof($mages) == 2)
                return true;
        }

        return false;

    }

    function canLevelUp()
    {
        return true;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate() 
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $dragons = DRDungeonDice::getDragonDice($items);
        return sizeof($dragons) > 0;
    }

    function executeUltimate($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $dragons = DRDungeonDice::getDragonDice($items);
        $dragons = DRItem::setZone($dragons, ZONE_BOX);
        $this->game->manager->updateItems($dragons);
        $this->game->notif->ultimateDiscardDragon($dragons);

        $this->game->gamestate->nextState('ultimate');

    }
}
