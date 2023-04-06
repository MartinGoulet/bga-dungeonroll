<?php

class DRCommandOpenChestScout extends DRCommandOpenChest
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates()
    {
        $allowedStates = array();

        $hero = $this->game->components->getActivePlayerHero();
        if ($hero instanceof DRScout) {
            $itemsInPlay = $this->game->components->getActivePlayerItems();
            $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
            if (!$hero->canSkipMonsterPhase() && sizeof($monsters) == 0) {
                $allowedStates[] = 'monsterPhase';
            }
        }

        return $allowedStates;
    }

    function execute($sub_command_id)
    {
        parent::execute($sub_command_id);

        $this->game->gamestate->nextState('nextPhase');
    }
}
