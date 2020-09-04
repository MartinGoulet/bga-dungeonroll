<?php

class DRCommandHeroUltimate extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    function getCommandInfo()
    {
        $hero = $this->game->components->getActivePlayerHero();
        $heroText = $hero->getCommandText();
        $info = parent::getCommandInfo();

        if($heroText != null && $heroText != "") {
            $info['text'] = $hero->getCommandText();
        } else {
            $info['commands'] = $hero->getCommands();
        }
        return $info;
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $hero = $this->game->components->getActivePlayerHero();
        return $this->game->vars->getIsHeroActivated() == false && $hero->canExecuteUltimate();
    }

    public function execute($sub_command_id)
    {
        $hero = $this->game->components->getActivePlayerHero();
        $this->game->vars->setIsHeroActivated(true);
        $this->game->notif->heroUltimate();
        $hero->executeUltimate($sub_command_id);
        
        if($hero->updatePossibleActionAfterUltimate()) {
            $this->game->notif->updatePossibleActions();
        }
    }
}
