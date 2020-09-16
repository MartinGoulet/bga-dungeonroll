<?php

class DRCommandDiceSelection extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('selectionDice');
    }

    public function canExecute()
    {        
        $hero = $this->game->components->getActivePlayerHero();
        return $hero->isSelectionDiceCorrect();
    }

    public function execute($sub_command_id)
    {
        // Next state
        $this->game->gamestate->nextState();
    }
}
