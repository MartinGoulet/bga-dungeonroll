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

        // All Skeletons become Potions
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRDungeonDice::isSkeleton', DIE_POTION);
        if (sizeof($changes)) {
            $this->game->notif->changeSkeletonToPotion($changes);
        };

    }

    function actionAfterRollingDiceWithScroll($dice) 
    {
        // All Skeletons become Potions
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRDungeonDice::isSkeleton', DIE_POTION);
        if (sizeof($changes)) {
            $this->game->notif->changeSkeletonToPotion($changes);
        };
    }

}
