<?php

class DRCommandFleeDungeon extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('monsterPhase', 'dragonPhase');
    }

    public function execute($sub_command_id)
    {
        // Reset the experience gained this turn
        $this->game->vars->setDungeonLevel(0);

        // Go to the next state
        $this->game->gamestate->nextState('fleeDungeon');
    }
}
