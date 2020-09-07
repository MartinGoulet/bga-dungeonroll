<?php

class DRDungeoneer extends DRScout
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

    function isReduceLevel()
    {
        return false;
    }

    public function canSkipMonsterPhase() 
    {
        return true;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        return in_array($this->getState(), array('monsterPhase', 'lootPhase', 'dragonPhase'));
    }

}
