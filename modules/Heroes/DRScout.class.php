<?php

class DRScout extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }
    
    /**
     * Game breaking rules
     */
    function canLevelUp()
    {
        return true;
    }

    public function getDiceForRollDungeonStep()
    {
        $level = $this->game->vars->getDungeonLevel();

        if($level > 3) {
            return null;
        }
        $items = $this->game->components->getItemsByZone("LEVEL_" . $level);
        $items = DRItem::setZone($items, DR_ZONE_BOX);
        return $items;
    }

    function isReduceLevel()
    {
        return true;
    }

    public function canSkipMonsterPhase() 
    {
        // He can skip his monster phase unless 3 dragon dice found.
        // Otherwise, he cannot use his ultimte
        $dragons = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_DRAGON_LAIR);
        return sizeof($dragons) < 3;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        return in_array($this->getState(), array('monsterPhase'));
    }

    function executeUltimate($sub_command_id)
    {

        // Ultimate like a Town Portal
        $this->game->notif->ultimateScout();

        // Reduce level dungeon by 1
        if($this->isReduceLevel()) {
            $points = $this->game->vars->decDungeonLevel();
        } else {
            $points = $this->game->vars->getDungeonLevel();
        }
        // Scoring
        $this->game->incPlayerScore($points);
        $this->game->notif->updateScorePlayer($points);
        $this->game->notif->updatedScores();

        // Stats
        $this->game->stats->incLevelCompleted($points);

        // Go to the next state
        $this->game->gamestate->nextState('townPortal');

    }

}
