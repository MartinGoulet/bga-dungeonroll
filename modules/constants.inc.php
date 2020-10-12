<?php

/*
 * Component types
 */

define("TYPE_PARTY_DIE", 1);
define("TYPE_DUNGEON_DIE", 2);
define("TYPE_TREASURE_TOKEN", 3);
define("TYPE_NOVICE_HERO", 4);
define("TYPE_MASTER_HERO", 5);

/*
 * Party Dice
 */

define('DIE_SCROLL', 1);
define('DIE_MAGE', 2);
define('DIE_CLERIC', 3);
define('DIE_FIGHTER', 4);
define('DIE_THIEF', 5);
define('DIE_CHAMPION', 6);
define('DIE_GENERIC_DRAGON', 7);

/*
 * Dungeon Dice
 */

define('DIE_DRAGON', 1);
define('DIE_POTION', 2);
define('DIE_OOZE', 3);
define('DIE_SKELETON', 4);
define('DIE_CHEST', 5);
define('DIE_GOBLIN', 6);

/*
 * Treasure Tokens
 */

define('TOKEN_VORPAL_SWORD', 1);
define('TOKEN_TALISMAN', 2);
define('TOKEN_SCEPTER_OF_POWER', 3);
define('TOKEN_THIEVES', 4);
define('TOKEN_SCROLL', 5);
define('TOKEN_RING_INVISIBILITY', 6);
define('TOKEN_DRAGON_SCALES', 7);
define('TOKEN_ELIXIR', 8);
define('TOKEN_DRAGON_BAIT', 9);
define('TOKEN_TOWN_PORTAL', 10);

/*
 * LOCATION (Zones)
 */

define('ZONE_PARTY', 'party');
define('ZONE_DUNGEON', 'dungeon');
define('ZONE_GRAVEYARD', 'graveyard');
define('ZONE_DRAGON_LAIR', 'dragon_lair');
define('ZONE_PLAY', 'play');
define('ZONE_INVENTORY', 'inventory');
define('ZONE_BOX', 'box');
define('ZONE_HERO', 'hero');
define('ZONE_DRAFT', 'draft');

/*
 * Game statistics constants
 */

define('STAT_XP_LEVEL_ID', 11);
define('STAT_XP_DRAGON_ID', 12);
define('STAT_XP_TREASURE_ID', 13);
define('STAT_XP_DRAGON_SCALE_ID', 14);
define('STAT_XP_TOWN_PORTAL_ID', 15);


define('STAT_XP_LEVEL', "player_xp_level");
define('STAT_XP_DRAGON', "player_xp_dragon");
define('STAT_XP_TREASURE', "player_xp_treasure");
define('STAT_XP_DRAGON_SCALE', "player_xp_dragon_scale");
define('STAT_XP_TOWN_PORTAL', "player_xp_town_portal");

define('STAT_NBR_DRAGON_KILL_ID', 21);
define('STAT_NBR_DRAGON_KILL', "player_nbr_dragon");

define('STAT_NBR_TREASURE_OPEN_ID', 22);
define('STAT_NBR_TREASURE_OPEN', "player_nbr_treasures");


/*
 * States
 */

define('STATE_GAME_OPTION', 2);
define('STATE_RANDOM_HERO', 3);
define('STATE_SELECT_HERO', 5);
define('STATE_NEXT_PLAYER_HERO', 6);
define('STATE_SETUP_MIRROR_GAME', 7);

define('STATE_INIT_PLAYER_TURN', 10);
define('STATE_FORMING_PARTY', 12);
define('STATE_POST_FORMING_PARTY_MERCENARY', 13);
define('STATE_POST_FORMING_PARTY_SCOUT', 14);
define('STATE_POST_FORMING_PARTY_LOEG_YLLAVYRE', 16);

define('STATE_DUNGEON_ROLL', 15);

define('STATE_PRE_MONSTER_PHASE', 19);
define('STATE_MONSTER_PHASE', 20);

define('STATE_PRE_LOOT_PHASE', 29);
define('STATE_LOOT_PHASE', 30);

define('STATE_CHOOSE_DIE', 35);

define('STATE_PRE_DRAGON_PHASE', 39);
define('STATE_DRAGON_PHASE', 40);

define('STATE_PRE_REGROUP_PHASE', 49);
define('STATE_REGROUP_PHASE', 50);

define('STATE_PRE_NEXT_PLAYER', 59);
define('STATE_NEXT_PLAYER', 60);

define('STATE_DISCARD_TREASURE', 70);
define('STATE_ULTIMATE_GUILD_LEADER', 71);
define('STATE_SELECTION_DICE', 72);
define('STATE_ULTIMATE_SZOPIN', 73);
define('STATE_ULTIMATE_TRISTAN', 74);

define('STATE_FINAL_SCORING', 98);

/*
 * Game global variables 
 */
define('GL_CURRENT_TURN_ID', 10);
define('GL_CURRENT_TURN', "table_current_turn");

define('GL_CURRENT_LEVEL_ID', 11);
define('GL_CURRENT_LEVEL', "player_current_level");

define('GL_MAX_TURN_ID', 12);
define('GL_MAX_TURN', 'table_max_turn');

define('GL_HERO_ACTIVATED_ID', 13);
define('GL_HERO_ACTIVATED', 'hero_activated');

define('GL_CHOOSE_DIE_COUNT_ID', 14);
define('GL_CHOOSE_DIE_COUNT', 'choose_die_count');

define('GL_CHOOSE_DIE_STATE_ID', 15);
define('GL_CHOOSE_DIE_STATE', 'choose_die_state');

define('GL_SPECIALTY_ONCE_PER_LEVEL_ID', 16);
define('GL_SPECIALTY_ONCE_PER_LEVEL', 'specialty_per_level');

define('GL_DRAGON_KILLED_THIS_TURN_ID', 17);
define('GL_DRAGON_KILLED_THIS_TURN', 'dragon_killed_this_turn');

define('GV_GAME_OPTION_ID', 100);
define('GV_GAME_OPTION', 'gameOption');

define('GV_GAME_EXPANSION_ID', 101);
define('GV_GAME_EXPANSION', 'Expansion');

define('GV_GAME_MIRROR_ID', 102);
define('GV_GAME_MIRROR', 'Mirror match');

/*
 * Game options 
 */

define('GAME_OPTION_RANDOM_HERO', 1);
define('GAME_OPTION_SELECT_HERO', 2);
define('GAME_OPTION_NO_HERO', 3);

define('GAME_EXPANSION_BASE', 1);
define('GAME_EXPANSION_PACK_1', 2);
define('GAME_EXPANSION_PACK_2', 3);
define('GAME_EXPANSION_BASE_PACK_1', 99);

define('GAME_MIRROR_NO', 1);
define('GAME_MIRROR_YES', 2);

