<?php

class DRCommandEndMonsterPhase extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('monsterPhase');
    }

    public function canExecute()
    {        
        if (!parent::canExecute()) return false;

        $items = $this->game->components->getActivePlayerItems();
        $monsters = DRDungeonDice::getMonsterDices($items);
        return sizeof($monsters) == 0;
    }

    public function execute($sub_command_id)
    {
        // Go to the next state
        $this->game->gamestate->nextState('nextPhase');
    }
}
