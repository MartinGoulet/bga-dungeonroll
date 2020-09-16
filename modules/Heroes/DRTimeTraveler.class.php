<?php

class DRTimeTraveler extends DRStandardHero
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
        return true;
    }

    public function getUltimateAllowedStates()
    {
        return array('dragonPhase');
    }

    /**
     * States
     */

    function stateBeforeFormingParty(&$dice)
    {
        $dice[0]['value'] = DIE_FIGHTER;
        $dice[1]['value'] = DIE_CLERIC;
        $dice[2]['value'] = DIE_MAGE;
        $dice[3]['value'] = DIE_THIEF;
        $dice[4]['value'] = DIE_SCROLL;
        $dice[5]['value'] = DIE_CHAMPION;
        $dice[6]['value'] = DIE_CHAMPION;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $tokens = DRTreasureToken::getTreasureTokens($items);
        return sizeof($tokens) == 1;
    }

    function executeUltimate($sub_command_id)
    {
        $command = $this->game->commands->getCommandByName('fightDragon');
        $command->execute(0);
    }
}
