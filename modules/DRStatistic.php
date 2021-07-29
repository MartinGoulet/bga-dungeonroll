<?php

require_once ("constants.inc.php");

class DRStatistic
{
    private $game;

    function __construct($game)
    {
        $this->game = $game;
    }

    /*
   * initStats: initialize statistics to 0 at start of game
   */
    public function initStats($players)
    {
        // $this->game->initStat('table', 'move', 0);

        foreach ($players as $player_id => $player) {

            $this->game->initStat('player', DR_STAT_XP_LEVEL, 0, $player_id);

            $this->game->initStat('player', DR_STAT_XP_TREASURE, 0, $player_id);
            $this->game->initStat('player', DR_STAT_XP_DRAGON, 0, $player_id);
            $this->game->initStat('player', DR_STAT_XP_DRAGON_SCALE, 0, $player_id);
            $this->game->initStat('player', DR_STAT_XP_TOWN_PORTAL, 0, $player_id);

            $this->game->initStat('player', DR_STAT_NBR_DRAGON_KILL, 0, $player_id);
            $this->game->initStat('player', DR_STAT_NBR_TREASURE_OPEN, 0, $player_id);
        }
    }

    public function getLevelCompleted($player_id) {
        return $this->game->getStat(DR_STAT_XP_LEVEL, $player_id);
    }

    public function getExpTreasure($player_id) {
        return $this->game->getStat(DR_STAT_XP_TREASURE, $player_id);
    }

    public function getExpDragon($player_id) {
        return $this->game->getStat(DR_STAT_XP_DRAGON, $player_id);
    }

    public function getExpDragonScale($player_id) {
        return $this->game->getStat(DR_STAT_XP_DRAGON_SCALE, $player_id);
    }

    public function getExpTownPortal($player_id) {
        return $this->game->getStat(DR_STAT_XP_TOWN_PORTAL, $player_id);
    }

    public function incLevelCompleted($nbr_level) {
        $player_id = $this->game->getActivePlayerId();
        $this->game->incStat($nbr_level, DR_STAT_XP_LEVEL, $player_id);
    }

    public function incDragonKilled($nbr_dragons) {
        $player_id = $this->game->getActivePlayerId();
        $this->game->incStat(1, DR_STAT_XP_DRAGON, $player_id);
        $this->game->incStat($nbr_dragons, DR_STAT_NBR_DRAGON_KILL, $player_id);
    }

    public function incTreasureOpen($nbr_treasures) {
        $player_id = $this->game->getActivePlayerId();
        $this->game->incStat($nbr_treasures, DR_STAT_NBR_TREASURE_OPEN, $player_id);
    }
}
