<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * DungeonRoll implementation : © Martin Goulet <martin.goulet@live.ca>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * dungeonroll.action.php
 *
 * DungeonRoll main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/dungeonroll/dungeonroll/myAction.html", ...)
 *
 */


class action_dungeonroll extends APP_GameAction
{
    // Constructor: please do not modify
    public function __default()
    {
        if (self::isArg('notifwindow')) {
            $this->view = "common_notifwindow";
            $this->viewArgs['table'] = self::getArg("table", AT_posint, true);
        } else {
            $this->view = "dungeonroll_dungeonroll";
            self::trace("Complete reinitialization of board game");
        }
    }

    public function executeCommand()
    {
        self::setAjaxMode();
        $this->game->checkAction('executeCommand');
        $command_id = self::getArg("id", AT_posint, true);
        $sub_command_id = self::getArg("sub", AT_posint, true);
        $this->game->executeCommand($command_id, $sub_command_id);
        self::ajaxResponse();
    }

    public function selectHero()
    {
        self::setAjaxMode();
        $this->game->checkAction('selectHero');
        $hero_id = self::getArg("hero_id", AT_posint, true);
        $this->game->selectHero($hero_id);
        self::ajaxResponse();
    }

    public function moveItem()
    {
        self::setAjaxMode();
        $this->game->checkAction('moveItem');
        $die_id = self::getArg("die_id", AT_posint, true);
        $this->game->moveItem($die_id);
        self::ajaxResponse();
    }
    
    public function chooseDieGain()
    {
        self::setAjaxMode();
        $this->game->checkAction('chooseDieGain');
        $type = self::getArg("type", AT_posint, true);
        $value = self::getArg("value", AT_posint, true);
        $this->game->chooseDieGain($type, $value);
        self::ajaxResponse();
    }
    
    public function selectGuildLeaderDice()
    {
        self::setAjaxMode();
        $this->game->checkAction('selectGuildLeaderDice');
        $party = self::getArg("party", AT_posint, true);
        $dungeon = self::getArg("dungeon", AT_posint, true);
        $this->game->selectGuildLeaderDice($party, $dungeon);
        self::ajaxResponse();
    }
}
