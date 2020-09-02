<?php

class DRUndeadViking extends DRViking
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

    /**
     * States
     */
    function stateAfterDungeonDiceRoll($dice)
    {
        // When rolling Dungeon dice, all Skeletons become Potions
        $changes = array();
        // &$die : Alter the die in parameter
        foreach ($dice as &$die) {
            if (DRDungeonDice::isSkeleton($die)) {
                $die['value'] = DIE_POTION;
                $changes[] = $die;
            }
        }

        // Notify the modification
        if (sizeof($changes) > 0) {
            $this->game->manager->updateItems($dice);
            $this->game->notif->changeSkeletonToPotion($changes);
        }

    }

}
