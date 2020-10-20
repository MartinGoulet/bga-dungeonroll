<?php

class DRCommandEndQuaffPhase extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('quaffPotion');
    }

    public function canExecute()
    {        
        if (!parent::canExecute()) return false;

        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD);
        $party = DRPartyDice::getPartyDice($items);
        return sizeof($party) == 0;
    }

    public function execute($sub_command_id)
    {
        // Go to the next state
        $this->game->gamestate->nextState('end');
    }
}
