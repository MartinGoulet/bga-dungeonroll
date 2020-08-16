<?php

class DRCommandSeekGlory extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('regroupPhase');
    }

    public function canExecute()
    {
        return parent::canExecute() && $this->game->vars->getDungeonLevel() < 10;
    }

    public function execute($sub_command_id)
    {
        // Go to the next state
        $this->game->gamestate->nextState('seekGlory');
    }
}
