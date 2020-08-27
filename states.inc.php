<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * DungeonRoll implementation : © Martin Goulet <martin.goulet@live.ca>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 * 
 * states.inc.php
 *
 * DungeonRoll game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

require_once("modules/constants.inc.php");

$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => array("" => STATE_GAME_OPTION)
    ),

    STATE_GAME_OPTION => array(
        "name" => "gameOption",
        "type" => "game",
        "action" => "stGameOption",
        "transitions" => array("random" => STATE_RANDOM_HERO, "select" => STATE_SELECT_HERO, "nohero" => STATE_INIT_PLAYER_TURN)
    ),

    STATE_RANDOM_HERO => array(
        "name" => "randomHero",
        "type" => "game",
        "action" => "stRandomHero",
        "transitions" => array("" => STATE_INIT_PLAYER_TURN)
    ),

    STATE_SELECT_HERO => array(
        "name" => "draftHeroes",
        "description" => clienttranslate('${actplayer} must select a hero'),
        "descriptionmyturn" => clienttranslate('${you} must select a hero'),
        "type" => "activeplayer",
        "args" => "argDraftHeroes",
        "possibleactions" => array("selectHero"),
        "transitions" => array("" => STATE_NEXT_PLAYER_HERO)
    ),


    STATE_NEXT_PLAYER_HERO => array(
        "name" => "nextDraftHero",
        "type" => "game",
        "action" => "stNextDraftHero",
        "transitions" => array("next" => STATE_SELECT_HERO, "end" => STATE_INIT_PLAYER_TURN)
    ),


    STATE_INIT_PLAYER_TURN => array(
        "name" => "initPlayerTurn",
        "type" => "game",
        "action" => "stInitPlayerTurn",
        "transitions" => array("" => STATE_FORMING_PARTY)
    ),

    // Phase when the player rolls all party dice
    STATE_FORMING_PARTY => array(
        "name" => "formingParty",
        "type" => "game",
        "action" => "stFormingParty",
        "transitions" => array("mercenary" => STATE_POST_FORMING_PARTY, "dungeon" => STATE_DUNGEON_ROLL)
    ),

    STATE_POST_FORMING_PARTY => array(
        "name" => "postFormingParty",
        "description" => clienttranslate('Forming party : ${actplayer} may re-roll any number of Party dice'),
        "descriptionmyturn" => clienttranslate('Forming party : ${you} may re-roll any number of Party dice'),
        "type" => "activeplayer",
        "args" => "argGenericPhasePlayerTurn",
        "possibleactions" => array("executeCommand", "moveItem"),
        "transitions" => array("" => STATE_DUNGEON_ROLL)
    ),

    STATE_DUNGEON_ROLL => array(
        "name" => "nextDungeonLevel",
        "type" => "game",
        "action" => "stRollDungeonDice",
        "transitions" => array("" => STATE_MONSTER_PHASE)
    ),

    STATE_MONSTER_PHASE => array(
        "name" => "monsterPhase",
        "description" => clienttranslate('Monster phase : ${actplayer} must defeat all monsters'),
        "descriptionmyturn" => clienttranslate('Monster phase : ${you} must defeat all monsters (one fight at a time)'),
        "type" => "activeplayer",
        "args" => "argGenericPhasePlayerTurn",
        "possibleactions" => array("moveItem", "executeCommand"),
        "updateGameProgression" => true,
        "transitions" => array(
            "nextPhase" => STATE_PRE_LOOT_PHASE,
            "fleeDungeon" => STATE_NEXT_PLAYER,
            "townPortal" => STATE_NEXT_PLAYER,
            "chooseDie" => STATE_CHOOSE_DIE,
        )
    ),

    STATE_PRE_LOOT_PHASE => array(
        "name" => "preLootPhase",
        "type" => "game",
        "action" => "stPreLootPhase",
        "transitions" => array("lootPhase" => STATE_LOOT_PHASE, "preDragonPhase" => STATE_PRE_DRAGON_PHASE)
    ),

    STATE_LOOT_PHASE => array(
        "name" => "lootPhase",
        "description" => clienttranslate('Loot phase : ${actplayer} may open Chests and quaff Potions'),
        "descriptionmyturn" => clienttranslate('Loot phase : ${you} may open Chests and quaff Potions'),
        "type" => "activeplayer",
        "args" => "argGenericPhasePlayerTurn",
        "possibleactions" => array("moveItem", "executeCommand"),
        "transitions" => array("end" => STATE_PRE_DRAGON_PHASE, "townPortal" => STATE_NEXT_PLAYER, "chooseDie" => STATE_CHOOSE_DIE)
    ),

    STATE_CHOOSE_DIE => array(
        "name" => "quaffPotion",
        "description" => clienttranslate('${actplayer} must choose which die to get (x${nbr})'),
        "descriptionmyturn" => clienttranslate('${you} must choose which die to get (x${nbr})'),
        "type" => "activeplayer",
        "args" => "argQuaffPotion",
        "possibleactions" => array("chooseDieGain"),
        "transitions" => array(
            "next" => STATE_CHOOSE_DIE,
            "monsterPhase" => STATE_MONSTER_PHASE,
            "lootPhase" => STATE_LOOT_PHASE,
            "dragonPhase" => STATE_DRAGON_PHASE,
            "regroupPhase" => STATE_REGROUP_PHASE
        )
    ),

    STATE_PRE_DRAGON_PHASE => array(
        "name" => "preDragonPhase",
        "type" => "game",
        "action" => "stPreDragonPhase",
        "transitions" => array("dragonPhase" => STATE_DRAGON_PHASE, "regroupPhase" => STATE_PRE_REGROUP_PHASE)
    ),

    STATE_DRAGON_PHASE => array(
        "name" => "dragonPhase",
        "description" => clienttranslate('Dragon phase : ${actplayer} must defeat the Dragon'),
        "descriptionmyturn" => clienttranslate('Dragon phase : ${you} must defeat the Dragon (${nbr} differents Companions are required)'),
        "type" => "activeplayer",
        "args" => "argDragonPhasePlayerTurn",
        "possibleactions" => array("moveItem", "executeCommand"),
        "transitions" => array(
            "killDragons" => STATE_PRE_REGROUP_PHASE,
            "fleeDungeon" => STATE_NEXT_PLAYER,
            "townPortal" => STATE_NEXT_PLAYER,
            "ringInvisibility" => STATE_PRE_REGROUP_PHASE,
            "chooseDie" => STATE_CHOOSE_DIE,
            "end" => STATE_PRE_LOOT_PHASE,
        )
    ),

    STATE_PRE_REGROUP_PHASE => array(
        "name" => "preRegroupPhase",
        "type" => "game",
        "action" => "stPreRegroupPhase",
        "transitions" => array("" => STATE_REGROUP_PHASE)
    ),

    STATE_REGROUP_PHASE => array(
        "name" => "regroupPhase",
        "description" => clienttranslate('Regroup phase : ${actplayer} must choose his or her destiny'),
        "descriptionmyturn" => clienttranslate('Regroup phase : ${you} must choose your destiny'),
        "type" => "activeplayer",
        "args" => "argGenericPhasePlayerTurn",
        "possibleactions" => array("executeCommand", "moveItem"),
        "transitions" => array(
            "retireTavern" => STATE_NEXT_PLAYER,
            "seekGlory" => STATE_DUNGEON_ROLL,
            "chooseDie" => STATE_CHOOSE_DIE,
        )
    ),

    // Next player
    STATE_NEXT_PLAYER => array(
        "name" => "nextPlayer",
        "type" => "game",
        "action" => "stNextPlayer",
        "updateGameProgression" => true,
        "transitions" => array("endGame" => STATE_FINAL_SCORING, "formingParty" => STATE_INIT_PLAYER_TURN)
    ),


    STATE_FINAL_SCORING => array(
        "name" => "finalScoring",
        "type" => "game",
        "action" => "stGameEndScoring",
        "updateGameProgression" => true,
        "transitions" => array("" => 99)
    ),

    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);
