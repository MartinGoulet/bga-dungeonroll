<?php

class DRCommandDiceSelection extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates()
    {
        return array('selectionDice');
    }

    public function canExecute()
    {
        $hero = $this->game->components->getActivePlayerHero();
        return $hero->isSelectionDiceCorrect();
    }

    public function execute($sub_command_id)
    {
        $hero = $this->game->components->getActivePlayerHero();
        if ($hero instanceof DRSzopin) {
            $this->game->gamestate->nextState("szopin");
        } elseif ($hero instanceof DRTristan) {
            $this->game->gamestate->nextState("tristan");
        } elseif ($hero instanceof DRGuildLeader || $hero instanceof DRGuildMaster) {
            $this->game->gamestate->nextState("guildLeader");
        }
        // Next state
    }
}
