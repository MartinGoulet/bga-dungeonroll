<?php

class DRTimeLady extends DRTimeTraveler
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
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $tokens = DRTreasureToken::getTreasureTokens($items);
        $scrolls = DRPartyDice::getScrollDice($items);
        $companions = DRPartyDice::getCompanionDice($items);
        return (sizeof($tokens) + sizeof($scrolls)) == 1 && sizeof($companions) == 0;
    }

    function executeUltimate($sub_command_id)
    {
        $command = $this->game->commands->getCommandByName('fightDragon');
        $command->execute(0);
    }
}
