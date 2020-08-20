<?php

class DRCommandEndDragonPhase extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('dragonPhase');
    }

    public function canExecute()
    {        
        if (!parent::canExecute()) return false;

        $items = $this->game->components->getActivePlayerUsableItems();
        $dragons = DRDungeonDice::getDragonDice($items);
        return sizeof($dragons) == 0;
    }

    public function execute($sub_command_id)
    {
        // Next state
        $this->game->gamestate->nextState('end');
    }
}
