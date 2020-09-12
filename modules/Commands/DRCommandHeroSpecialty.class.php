<?php

class DRCommandHeroSpecialty extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        $hero = $this->game->components->getActivePlayerHero();
        return $hero->getSpecialtyAllowedStates();
    }

    function getCommandInfo()
    {
        $hero = $this->game->components->getActivePlayerHero();
        $info = parent::getCommandInfo();
        $info['text'] = $hero->getCommandSpecialty();
        return $info;
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $hero = $this->game->components->getActivePlayerHero();
        return $hero->canExecuteSpecialty();
    }

    public function execute($sub_command_id)
    {
        $hero = $this->game->components->getActivePlayerHero();
        $hero->executeSpecialty($sub_command_id);
        $this->game->notif->updatePossibleActions();
    }
}
