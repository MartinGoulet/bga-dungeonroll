<?php

class DREnchantress extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    protected function getNumberOfMonsterToTransform()
    {
        return 1;
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
        return $monstersTypeCount == 1 && sizeof($scrolls) == 1;
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

    function canDefeatDragon()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        $scrolls = DRUtils::filter($itemsInPlay, function ($item) {
            return DRPartyDice::isScroll($item) || DRTreasureToken::isScroll($item);
        });
        
        $companions = DRItem::getCompanions($itemsInPlay);
        $distinctType = array_unique(DRItem::getCompanionTypes($companions));

        return sizeof($companions) + sizeof($scrolls) == 3 &&
               sizeof($distinctType) == sizeof($companions);
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
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        return sizeof($monsters) <= $this->getNumberOfMonsterToTransform() && sizeof($monsters) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monsters = DRItem::setZone($monsters, ZONE_BOX);

        // Change first monster into a potion
        $monsters[0]['zone'] = ZONE_DUNGEON;
        $monsters[0]['value'] = DIE_POTION;

        // Return potion to the dungeon zone
        $this->game->manager->updateItems($monsters);
        $this->game->notif->ultimateEnchantressBeguiler($monsters, 1);

        $this->game->gamestate->nextState('ultimate');
    }
}
