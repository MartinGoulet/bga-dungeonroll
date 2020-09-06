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
        $this->applySpecialty($dice);
    }

    function actionAfterRollingDiceWithScroll($dice) 
    {
        $this->applySpecialty($dice);
    }

    function applySpecialty($dice) 
    {
        // All Skeletons become Potions
        $skeletons = DRItem::getSameAs($dice, DRDungeonDice::getDie(DIE_SKELETON));
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRDungeonDice::isSkeleton', DIE_POTION);
        if (sizeof($changes)) {
            $this->game->notif->changeSkeletonToPotion($changes, $skeletons);
        };
    }

}
