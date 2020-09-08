<?php

class DRCommandRetireTavern extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('regroupPhase');
    }

    public function execute($sub_command_id)
    {
        // Show the message before the update score point
        $this->game->notif->retireTavern();

        $points = $this->game->vars->getDungeonLevel();
        $this->game->incPlayerScore($points);
        $this->game->notif->updateScorePlayer($points);
        $this->game->notif->updatedScores();

        // Stats
        $this->game->stats->incLevelCompleted($points);


        // Next state
        $this->game->gamestate->nextState('retireTavern');
    }
}
