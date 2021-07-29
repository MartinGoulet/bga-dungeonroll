<?php

class DRCassandra extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }



    function stateAfterFormingParty(&$dice)
    {
        // Begin each delve exhausted
        $this->game->vars->setIsHeroActivated(true);
        $this->game->notif->heroUltimate();
    }

    function afterQuaffPotion()
    {
        $this->game->vars->setIsHeroActivated(false);
        $this->game->notif->refreshCassandra(DRDungeonDice::getDie(DR_DIE_POTION));
    }

    /**
     * Game breaking rules
     */
    function canDefeatMonster()
    {
        // If hero is not exhauted, use normal rules
        if (!$this->game->vars->getIsHeroActivated()) {
            return false;
        }

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $companions = DRItem::getCompanions($itemsInPlay);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monsterGroup = $this->groupByDiceValue($monsters);

        $scrolls = DRUtils::filter($itemsInPlay, 'DRItem::isScroll');
        if(sizeof($scrolls) > 0) {
            return false;
        }

        // Use normal rules if not single hero
        if(sizeof($companions) != 1 || sizeof($monsters) == 1) {
            return false;
        } else if(sizeof($monsters) == 2) {
            return true;
        } else if(sizeof($monsterGroup) == 3) {
            // Can't fight 3 groups
            return false;
        }

        $companion = $companions[0];
        if(!array_key_exists(DR_DIE_GOBLIN, $monsterGroup)) {
            $monsterGroup[DR_DIE_GOBLIN] = array();
        }
        if(!array_key_exists(DR_DIE_OOZE, $monsterGroup)) {
            $monsterGroup[DR_DIE_OOZE] = array();
        }
        if(!array_key_exists(DR_DIE_SKELETON, $monsterGroup)) {
            $monsterGroup[DR_DIE_SKELETON] = array();
        }

        $values = array(
            sizeof($monsterGroup[DR_DIE_GOBLIN]),
            sizeof($monsterGroup[DR_DIE_OOZE]),
            sizeof($monsterGroup[DR_DIE_SKELETON]),
        );
        sort($values);
        if(!($values[2] >= 1 && $values[1] <= 1 && $values[0] == 0)) {
            return false;
        }

        if(DRItem::isMage($companion)) {
            return sizeof($monsterGroup[DR_DIE_OOZE]) > 1;
        } else if(DRItem::isFighter($companion)) {
            return sizeof($monsterGroup[DR_DIE_GOBLIN]) > 1;
        } else if(DRItem::isCleric($companion)) {
            return sizeof($monsterGroup[DR_DIE_SKELETON]) > 1;
        } else if(DRPartyDice::isChampion($companion)) {
            return true;
        }

        return false;
        
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);

        return sizeof($itemsInPlay) == 1 &&
            (DRDungeonDice::isChest($itemsInPlay[0]) ||
                DRDungeonDice::isPotion($itemsInPlay[0]));
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $itemsInPlay = DRItem::setZone($itemsInPlay, DR_ZONE_BOX);
        $this->game->manager->updateItems($itemsInPlay);
        $this->game->notif->ultimateCassandra($itemsInPlay);
    }
}
