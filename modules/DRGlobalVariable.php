<?php

require_once("constants.inc.php");

class DRGlobalVariable
{
    private $game;

    function __construct($game)
    {
        $this->game = $game;
    }

    function initVariables($players)
    {
        $this->game->setGameStateInitialValue(GL_CURRENT_TURN, 0);
        $this->game->setGameStateInitialValue(GL_CURRENT_LEVEL, 0);
        $this->game->setGameStateInitialValue(GL_MAX_TURN, sizeof($players) * 3);
        $this->game->setGameStateInitialValue(GL_CHOOSE_DIE_COUNT, 0);
        $this->game->setGameStateInitialValue(GL_CHOOSE_DIE_STATE, STATE_LOOT_PHASE);
        $this->game->setGameStateInitialValue(GL_HERO_ACTIVATED, 0);
        $this->game->setGameStateInitialValue(GL_SPECIALTY_ONCE_PER_LEVEL, 0);
        $this->game->setGameStateInitialValue(GL_DRAGON_KILLED_THIS_TURN, 0);
    }

    /**
     * Getters
     */

    function getChooseDieCount()
    {
        return $this->game->getGameStateValue(GL_CHOOSE_DIE_COUNT);
    }

    function getChooseDieState()
    {
        $state_id = $this->game->getGameStateValue(GL_CHOOSE_DIE_STATE);
        switch ($state_id) {

            case STATE_MONSTER_PHASE:
                return 'monsterPhase';

            case STATE_LOOT_PHASE:
                return 'lootPhase';

            case STATE_DRAGON_PHASE:
                return 'dragonPhase';

            case STATE_REGROUP_PHASE:
                return 'regroupPhase';

            case STATE_NEXT_PLAYER:
                return 'nextPlayer';
        }
    }

    function getCurrentTurn()
    {
        return $this->game->getGameStateValue(GL_CURRENT_TURN);
    }

    function getDungeonLevel()
    {
        return $this->game->getGameStateValue(GL_CURRENT_LEVEL);
    }

    function getMaxTurn()
    {
        return $this->game->getGameStateValue(GL_MAX_TURN);
    }

    function getGameOption()
    {
        return $this->game->getGameStateValue(GV_GAME_OPTION);
    }

    function getGameExpansion()
    {
        return $this->game->getGameStateValue(GV_GAME_EXPANSION);
    }

    function getIsGameMirror()
    {
        return $this->game->getGameStateValue(GV_GAME_MIRROR) == GAME_MIRROR_YES;
    }

    function getIsHeroActivated()
    {
        return $this->game->getGameStateValue(GL_HERO_ACTIVATED) == 1;
    }

    function getIsSpecialtyActivated()
    {
        return $this->game->getGameStateValue(GL_SPECIALTY_ONCE_PER_LEVEL) == 1;
    }

    function getIsDragonKilledThisTurn()
    {
        return $this->game->getGameStateValue(GL_DRAGON_KILLED_THIS_TURN) == 1;
    }

    /**
     * Increments
     */

    function incCurrentTurn()
    {
        return $this->game->incGameStateValue(GL_CURRENT_TURN, 1);
    }

    function incDungeonLevel()
    {
        return $this->game->incGameStateValue(GL_CURRENT_LEVEL, 1);
    }


    /**
     * Decrements
     */
    function decChooseDieCount()
    {
        return $this->game->incGameStateValue(GL_CHOOSE_DIE_COUNT, -1);
    }

    function decDungeonLevel()
    {
        return $this->game->incGameStateValue(GL_CURRENT_LEVEL, -1);
    }

    /**
     * Setters
     */

    function setChooseDieCount($count)
    {
        $this->game->setGameStateValue(GL_CHOOSE_DIE_COUNT, $count);
    }

    function setChooseDieState($state_name)
    {
        switch ($state_name) {

            case 'monsterPhase':
                $this->game->setGameStateValue(GL_CHOOSE_DIE_STATE, STATE_MONSTER_PHASE);
                break;

            case 'lootPhase':
                $this->game->setGameStateValue(GL_CHOOSE_DIE_STATE, STATE_LOOT_PHASE);
                break;

            case 'dragonPhase':
                $this->game->setGameStateValue(GL_CHOOSE_DIE_STATE, STATE_DRAGON_PHASE);
                break;

            case 'regroupPhase':
                $this->game->setGameStateValue(GL_CHOOSE_DIE_STATE, STATE_REGROUP_PHASE);
                break;

            case 'nextPlayer':
                $this->game->setGameStateValue(GL_CHOOSE_DIE_STATE, STATE_NEXT_PLAYER);
                break;
        }
    }

    function setDungeonLevel($level)
    {
        $this->game->setGameStateValue(GL_CURRENT_LEVEL, $level);
    }

    function setIsHeroActivated($isActivated)
    {
        if ($isActivated) {
            $value = 1;
        } else {
            $value = 0;
        }
        $this->game->setGameStateValue(GL_HERO_ACTIVATED, $value);
    }

    function setIsSpecialtyActivated($isActivated)
    {
        if ($isActivated) {
            $value = 1;
        } else {
            $value = 0;
        }
        $this->game->setGameStateValue(GL_SPECIALTY_ONCE_PER_LEVEL, $value);
    }

    function setIsDragonKilledThisTurn($isActivated)
    {
        if ($isActivated) {
            $value = 1;
        } else {
            $value = 0;
        }
        $this->game->setGameStateValue(GL_DRAGON_KILLED_THIS_TURN, $value);
    }

}
