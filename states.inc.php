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
        "transitions" => array(
            "mirror" => STATE_SETUP_MIRROR_GAME,
            "standard" => STATE_INIT_PLAYER_TURN
        )
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
        "transitions" => array(
            "mirror" => STATE_SETUP_MIRROR_GAME,
            "next" => STATE_SELECT_HERO, 
            "end" => STATE_INIT_PLAYER_TURN,
        )
    ),

    STATE_SETUP_MIRROR_GAME =>array(
        "name" => "setupMirrorGame",
        "type" => "game",
        "action" => "stSetupMirrorGame",
        "transitions" => array("" => STATE_INIT_PLAYER_TURN)
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
        "transitions" => array(
            "mercenary" => STATE_POST_FORMING_PARTY_MERCENARY, 
            "scout" => STATE_POST_FORMING_PARTY_SCOUT,
            "loeg_yllavyre" => STATE_POST_FORMING_PARTY_LOEG_YLLAVYRE,
            "dungeon" => STATE_DUNGEON_ROLL
        )
    ),

    STATE_POST_FORMING_PARTY_MERCENARY => array(
        "name" => "postFormingParty",
        "description" => clienttranslate('Forming party : ${actplayer} may re-roll any number of Party dice'),
        "descriptionmyturn" => clienttranslate('Forming party : ${you} may re-roll any number of Party dice'),
        "type" => "activeplayer",
        "args" => "argGenericPhasePlayerTurn",
        "possibleactions" => array("executeCommand", "moveItem"),
        "transitions" => array("" => STATE_DUNGEON_ROLL)
    ),

    STATE_POST_FORMING_PARTY_SCOUT => array(
        "name" => "postFormingPartyScout",
        "description" => clienttranslate('Forming party : ${actplayer} must choose ${nbr} dungeon dice for level ${nbr}'),
        "descriptionmyturn" => clienttranslate('Forming party : ${you} must choose ${nbr} dungeon dice for level ${nbr}'),
        "type" => "activeplayer",
        "args" => "argScoutPhasePlayerTurn",
        "possibleactions" => array("executeCommand", "moveItem"),
        "transitions" => array(
            "next" => STATE_POST_FORMING_PARTY_SCOUT,
            "end" => STATE_DUNGEON_ROLL,
        )
    ),

    STATE_POST_FORMING_PARTY_LOEG_YLLAVYRE => array(
        "name" => "postFormingPartyLeogYllavyre",
        "description" => clienttranslate('Forming party : ${actplayer} must select 2 dice to set to scrolls'),
        "descriptionmyturn" => clienttranslate('Forming party : ${you} must select 2 dice to set to scrolls'),
        "type" => "activeplayer",
        "args" => "argGenericPhasePlayerTurn",
        "possibleactions" => array("executeCommand", "moveItem"),
        "transitions" => array("" => STATE_DUNGEON_ROLL)
    ),

    STATE_DUNGEON_ROLL => array(
        "name" => "nextDungeonLevel",
        "type" => "game",
        "action" => "stRollDungeonDice",
        "transitions" => array("" => STATE_PRE_MONSTER_PHASE)
    ),

    STATE_PRE_MONSTER_PHASE=> array(
        "name" => "preMonsterPhase",
        "type" => "game",
        "action" => "stPreMonsterPhase",
        "transitions" => array("monsterPhase" => STATE_MONSTER_PHASE, "preLootPhase" => STATE_PRE_LOOT_PHASE)
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
            "scroll" => STATE_PRE_MONSTER_PHASE,
            "ultimate" => STATE_PRE_MONSTER_PHASE,
            "fight" => STATE_PRE_MONSTER_PHASE,
            "chest" => STATE_PRE_MONSTER_PHASE, // For Paladin
            "dragonBait" => STATE_PRE_LOOT_PHASE,
            "nextPhase" => STATE_PRE_LOOT_PHASE,
            "fleeDungeon" => STATE_PRE_NEXT_PLAYER,
            "townPortal" => STATE_PRE_NEXT_PLAYER,
            'discardTreasures' => STATE_DISCARD_TREASURE,
            "chooseDie" => STATE_CHOOSE_DIE,
            "guildLeader" => STATE_SELECTION_DICE,
            "szopin" => STATE_SELECTION_DICE,
            "tristan" => STATE_SELECTION_DICE,
            "alexandra" => STATE_SELECTION_DICE,
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
        "transitions" => array(
            "scroll" => STATE_PRE_MONSTER_PHASE,
            "ultimate" => STATE_PRE_LOOT_PHASE,
            "fleeDungeon" => STATE_PRE_NEXT_PLAYER,
            "chest" => STATE_PRE_LOOT_PHASE, 
            "end" => STATE_PRE_DRAGON_PHASE, 
            "townPortal" => STATE_PRE_NEXT_PLAYER, 
            'discardTreasures' => STATE_DISCARD_TREASURE,
            "guildLeader" => STATE_SELECTION_DICE,
            "szopin" => STATE_SELECTION_DICE,
            "tristan" => STATE_SELECTION_DICE,
            "alexandra" => STATE_SELECTION_DICE,
            "chooseDie" => STATE_CHOOSE_DIE
        )
    ),

    STATE_CHOOSE_DIE => array(
        "name" => "quaffPotion",
        "description" => clienttranslate('${actplayer} must choose which die to get (x${nbr})'),
        "descriptionmyturn" => clienttranslate('${you} must choose which die to get (x${nbr})'),
        "type" => "activeplayer",
        "args" => "argQuaffPotion",
        "possibleactions" => array("chooseDieGain", "executeCommand"),
        "transitions" => array(
            "next" => STATE_CHOOSE_DIE,
            "end" => STATE_PRE_MONSTER_PHASE,
            "monsterPhase" => STATE_MONSTER_PHASE,
            "lootPhase" => STATE_PRE_LOOT_PHASE,
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
            "scroll" => STATE_DRAGON_PHASE,
            "ultimate" => STATE_PRE_DRAGON_PHASE,
            "killDragons" => STATE_PRE_REGROUP_PHASE,
            "fleeDungeon" => STATE_PRE_NEXT_PLAYER,
            "townPortal" => STATE_PRE_NEXT_PLAYER,
            "ringInvisibility" => STATE_PRE_REGROUP_PHASE,
            "chooseDie" => STATE_CHOOSE_DIE,
            'discardTreasures' => STATE_DISCARD_TREASURE,
            "end" => STATE_PRE_LOOT_PHASE,
            "guildLeader" => STATE_SELECTION_DICE,
            "szopin" => STATE_SELECTION_DICE,
            "tristan" => STATE_SELECTION_DICE,
            "alexandra" => STATE_SELECTION_DICE,
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
            "retireTavern" => STATE_PRE_NEXT_PLAYER,
            'discardTreasures' => STATE_DISCARD_TREASURE,
            "seekGlory" => STATE_DUNGEON_ROLL,
            "chooseDie" => STATE_CHOOSE_DIE,
            "ultimate" => STATE_PRE_MONSTER_PHASE,
        )
    ),

    // Next player
    STATE_PRE_NEXT_PLAYER => array(
        "name" => "preNextPlayer",
        "type" => "game",
        "action" => "stPreNextPlayer",
        "transitions" => array(
            "next" => STATE_NEXT_PLAYER, 
            "discardTreasures" => STATE_DISCARD_TREASURE
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

    STATE_DISCARD_TREASURE => array(
        "name" => "discardTreasure",
        "description" => clienttranslate('${actplayer} must choose which Treasure Token to discard (x${nbr})'),
        "descriptionmyturn" => clienttranslate('${you} must choose which Treasure Token to discard (x${nbr})'),
        "type" => "activeplayer",
        "args" => "argDiscardTreasure",
        "possibleactions" => array("executeCommand", "moveItem"),
        "transitions" => array(
            "monsterPhase" => STATE_MONSTER_PHASE,
            "lootPhase" => STATE_PRE_LOOT_PHASE,
            "dragonPhase" => STATE_PRE_DRAGON_PHASE,
            "regroupPhase" => STATE_PRE_REGROUP_PHASE,
            "nextPlayer" => STATE_NEXT_PLAYER,
        )
    ),

    STATE_SELECTION_DICE => array(
        "name" => "selectionDice",
        "description" => clienttranslate('${actplayer} must select ${selection}'),
        "descriptionmyturn" => clienttranslate('${you} must select ${selection}'),
        "type" => "activeplayer",
        "args" => "argSelectionDice",
        "possibleactions" => array("moveItem", "executeCommand"),
        "transitions" => array(
            "szopin" => STATE_ULTIMATE_SZOPIN,
            "guildLeader" => STATE_ULTIMATE_GUILD_LEADER,
            "tristan" => STATE_ULTIMATE_TRISTAN,
            "alexandra" => STATE_SPECIALTY_ALEXANDRA,
        )
    ),

    STATE_ULTIMATE_SZOPIN => array(
        "name" => "szopinUltimate",
        "type" => "game",
        "action" => "stUltimateSzopin",
        "transitions" => array("" => STATE_PRE_MONSTER_PHASE)
    ),

    STATE_ULTIMATE_TRISTAN => array(
        "name" => "tristanUltimate",
        "type" => "game",
        "action" => "stUltimateTristan",
        "transitions" => array("" => STATE_PRE_MONSTER_PHASE)
    ),

    STATE_ULTIMATE_GUILD_LEADER => array(
        "name" => "guildLeaderUltimate",
        "description" => clienttranslate('${actplayer} must choose which face to get'),
        "descriptionmyturn" => clienttranslate('${you} must choose which face to get'),
        "type" => "activeplayer",
        "args" => "argUltimateGuildLeader",
        "possibleactions" => array("selectGuildLeaderDice"),
        "transitions" => array(
            "" => STATE_PRE_MONSTER_PHASE,
        )
    ),

    STATE_SPECIALTY_ALEXANDRA => array(
        "name" => "aleandraSpecialty",
        "type" => "game",
        "action" => "stSpecialtyAlexandra",
        "transitions" => array("" => STATE_PRE_MONSTER_PHASE)
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
