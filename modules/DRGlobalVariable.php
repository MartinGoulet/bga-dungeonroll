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
        $this->game->setGameStateInitialValue(DR_GL_CURRENT_TURN, 0);
        $this->game->setGameStateInitialValue(DR_GL_CURRENT_LEVEL, 0);
        $this->game->setGameStateInitialValue(DR_GL_MAX_TURN, sizeof($players) * 3);
        $this->game->setGameStateInitialValue(DR_GL_CHOOSE_DR_DIE_COUNT, 0);
        $this->game->setGameStateInitialValue(DR_GL_CHOOSE_DR_DIE_STATE, DR_STATE_LOOT_PHASE);
        $this->game->setGameStateInitialValue(DR_GL_HERO_ACTIVATED, 0);
        $this->game->setGameStateInitialValue(DR_GL_SPECIALTY_ONCE_PER_LEVEL, 0);
        $this->game->setGameStateInitialValue(DR_GL_DRAGON_KILLED_THIS_TURN, 0);
        $this->game->setGameStateInitialValue(DR_GL_BERSERKER_ULTIMATE, 0);
    }

    /**
     * Getters
     */

    function getChooseDieCount()
    {
        return $this->game->getGameStateValue(DR_GL_CHOOSE_DR_DIE_COUNT);
    }

    function getChooseDieState()
    {
        $state_id = $this->game->getGameStateValue(DR_GL_CHOOSE_DR_DIE_STATE);
        switch ($state_id) {

            case DR_STATE_MONSTER_PHASE:
                return 'monsterPhase';

            case DR_STATE_LOOT_PHASE:
                return 'lootPhase';

            case DR_STATE_DRAGON_PHASE:
                return 'dragonPhase';

            case DR_STATE_REGROUP_PHASE:
                return 'regroupPhase';

            case DR_STATE_NEXT_PLAYER:
                return 'nextPlayer';
        }
    }

    function getCurrentTurn()
    {
        return $this->game->getGameStateValue(DR_GL_CURRENT_TURN);
    }

    function getDungeonLevel()
    {
        return $this->game->getGameStateValue(DR_GL_CURRENT_LEVEL);
    }

    function getMaxTurn()
    {
        return $this->game->getGameStateValue(DR_GL_MAX_TURN);
    }

    function getGameOption()
    {
        return $this->game->getGameStateValue(DR_GV_GAME_OPTION);
    }

    function getGameExpansion()
    {
        return $this->game->getGameStateValue(DR_GV_GAME_EXPANSION);
    }

    function getIsGameMirror()
    {
        return $this->game->getGameStateValue(DR_GV_GAME_MIRROR) == DR_GAME_MIRROR_YES;
    }

    function getIsHeroActivated()
    {
        return $this->game->getGameStateValue(DR_GL_HERO_ACTIVATED) == 1;
    }

    function getIsSpecialtyActivated()
    {
        return $this->game->getGameStateValue(DR_GL_SPECIALTY_ONCE_PER_LEVEL) == 1;
    }

    function getIsDragonKilledThisTurn()
    {
        return $this->game->getGameStateValue(DR_GL_DRAGON_KILLED_THIS_TURN) == 1;
    }

    function getIsBerserkerUltimate()
    {
        return $this->game->getGameStateValue(DR_GL_BERSERKER_ULTIMATE) == 1;
    }

    /**
     * Increments
     */

    function incCurrentTurn()
    {
        return $this->game->incGameStateValue(DR_GL_CURRENT_TURN, 1);
    }

    function incDungeonLevel()
    {
        return $this->game->incGameStateValue(DR_GL_CURRENT_LEVEL, 1);
    }


    /**
     * Decrements
     */
    function decChooseDieCount()
    {
        return $this->game->incGameStateValue(DR_GL_CHOOSE_DR_DIE_COUNT, -1);
    }

    function decDungeonLevel()
    {
        return $this->game->incGameStateValue(DR_GL_CURRENT_LEVEL, -1);
    }

    /**
     * Setters
     */

    function setChooseDieCount($count)
    {
        $this->game->setGameStateValue(DR_GL_CHOOSE_DR_DIE_COUNT, $count);
    }

    function setChooseDieState($state_name)
    {
        switch ($state_name) {

            case 'monsterPhase':
                $this->game->setGameStateValue(DR_GL_CHOOSE_DR_DIE_STATE, DR_STATE_MONSTER_PHASE);
                break;

            case 'lootPhase':
                $this->game->setGameStateValue(DR_GL_CHOOSE_DR_DIE_STATE, DR_STATE_LOOT_PHASE);
                break;

            case 'dragonPhase':
                $this->game->setGameStateValue(DR_GL_CHOOSE_DR_DIE_STATE, DR_STATE_DRAGON_PHASE);
                break;

            case 'regroupPhase':
                $this->game->setGameStateValue(DR_GL_CHOOSE_DR_DIE_STATE, DR_STATE_REGROUP_PHASE);
                break;

            case 'nextPlayer':
                $this->game->setGameStateValue(DR_GL_CHOOSE_DR_DIE_STATE, DR_STATE_NEXT_PLAYER);
                break;
        }
    }

    function setDungeonLevel($level)
    {
        $this->game->setGameStateValue(DR_GL_CURRENT_LEVEL, $level);
    }

    function setIsHeroActivated($isActivated)
    {
        if ($isActivated) {
            $value = 1;
        } else {
            $value = 0;
        }
        $this->game->setGameStateValue(DR_GL_HERO_ACTIVATED, $value);
    }

    function setIsSpecialtyActivated($isActivated)
    {
        if ($isActivated) {
            $value = 1;
        } else {
            $value = 0;
        }
        $this->game->setGameStateValue(DR_GL_SPECIALTY_ONCE_PER_LEVEL, $value);
    }

    function setIsDragonKilledThisTurn($isActivated)
    {
        if ($isActivated) {
            $value = 1;
        } else {
            $value = 0;
        }
        $this->game->setGameStateValue(DR_GL_DRAGON_KILLED_THIS_TURN, $value);
    }

    function setIsBerserkerUltimate($isActivated)
    {
        if ($isActivated) {
            $value = 1;
        } else {
            $value = 0;
        }
        $this->game->setGameStateValue(DR_GL_BERSERKER_ULTIMATE, $value);
    }

}
