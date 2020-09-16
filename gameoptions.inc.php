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
 * gameoptions.inc.php
 *
 * DungeonRoll game options description
 * 
 * In this file, you can define your game options (= game variants).
 *   
 * Note: If your game has no variant, you don't have to modify this file.
 *
 * Note²: All options defined in this file should have a corresponding "game state labels"
 *        with the same ID (see "initGameStateLabels" in dungeonroll.game.php)
 *
 * !! It is not a good idea to modify this file when a game is running !!
 *
 */

require_once('modules/constants.inc.php');

$game_options = array(

    /*
    
    // note: game variant ID should start at 100 (ie: 100, 101, 102, ...). The maximum is 199.
    100 => array(
                'name' => totranslate('my game option'),    
                'values' => array(

                            // A simple value for this option:
                            1 => array( 'name' => totranslate('option 1') )

                            // A simple value for this option.
                            // If this value is chosen, the value of "tmdisplay" is displayed in the game lobby
                            2 => array( 'name' => totranslate('option 2'), 'tmdisplay' => totranslate('option 2') ),

                            // Another value, with other options:
                            //  description => this text will be displayed underneath the option when this value is selected to explain what it does
                            //  beta=true => this option is in beta version right now.
                            //  nobeginner=true  =>  this option is not recommended for beginners
                            3 => array( 'name' => totranslate('option 3'), 'description' => totranslate('this option does X'), 'beta' => true, 'nobeginner' => true )
                        )
            )

    */

    GV_GAME_OPTION_ID => array(
        'name' => totranslate('Game option'),
        'values' => array(
            GAME_OPTION_RANDOM_HERO => array(
                'name' => totranslate('Random hero'),
                'tmdisplay' => totranslate('Random hero')
            ),
            GAME_OPTION_SELECT_HERO => array(
                'name' => totranslate('Select hero'),
                'tmdisplay' => totranslate('Select hero')
            ),
            GAME_OPTION_NO_HERO => array(
                'name' => totranslate('No hero'),
                'tmdisplay' => totranslate('No hero')
            )
        )
    ),

    GV_GAME_EXPANSION_ID => array(
        'name' => totranslate('Expansions'),
        'values' => array(
            GAME_EXPANSION_BASE => array(
                'name' => totranslate('Base game'),
                'tmdisplay' => totranslate('Base game')
            ),
            GAME_EXPANSION_PACK_1 => array(
                'name' => totranslate('Hero pack #1'),
                'tmdisplay' => totranslate('Hero pack #1')
            ),
            GAME_EXPANSION_BASE_PACK_1 => array(
                'name' => totranslate('All heroes'),
                'tmdisplay' => totranslate('All heroes'),
                'description' => totranslate('Base game, Hero pack #1 and Promo')
            ),
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => GV_GAME_OPTION_ID,
                'value' => array(GAME_OPTION_RANDOM_HERO, GAME_OPTION_SELECT_HERO)
            ),
        ),
    ),

    GV_GAME_MIRROR_ID => array(
        'name' => totranslate('Mirror match'),
        'values' => array(
            GAME_MIRROR_NO => array(
                'name' => totranslate('No')
            ),
            GAME_MIRROR_YES => array(
                'name' => totranslate('Yes'),
                'tmdisplay' => totranslate('Mirror match'),
                'description' => totranslate('All players will use the same hero')
            ),
        ),
        'displaycondition' => array(
            array(
                'type' => 'otheroption',
                'id' => GV_GAME_OPTION_ID,
                'value' => array(GAME_OPTION_RANDOM_HERO, GAME_OPTION_SELECT_HERO)
            ),
        ),
        'startcondition' => array(
            GAME_MIRROR_NO => array(),
            GAME_MIRROR_YES => array(
                array(
                    'type' => 'minplayers',
                    'value' => 2,
                    'message' => totranslate('Mirror match is available for 2 or more players.')
                )
            ),
        )
    )

);
