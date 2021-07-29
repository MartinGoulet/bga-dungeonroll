<?php

/*
 * Component types
 */

define("DR_TYPE_PARTY_DIE", 1);
define("DR_TYPE_DUNGEON_DIE", 2);
define("DR_TYPE_TREASURE_TOKEN", 3);
define("DR_TYPE_NOVICE_HERO", 4);
define("DR_TYPE_MASTER_HERO", 5);

/*
 * Party Dice
 */

define('DR_DIE_SCROLL', 1);
define('DR_DIE_MAGE', 2);
define('DR_DIE_CLERIC', 3);
define('DR_DIE_FIGHTER', 4);
define('DR_DIE_THIEF', 5);
define('DR_DIE_CHAMPION', 6);
define('DR_DIE_GENERIC_DRAGON', 7);

/*
 * Dungeon Dice
 */

define('DR_DIE_DRAGON', 1);
define('DR_DIE_POTION', 2);
define('DR_DIE_OOZE', 3);
define('DR_DIE_SKELETON', 4);
define('DR_DIE_CHEST', 5);
define('DR_DIE_GOBLIN', 6);

/*
 * Treasure Tokens
 */

define('DR_TOKEN_VORPAL_SWORD', 1);
define('DR_TOKEN_TALISMAN', 2);
define('DR_TOKEN_SCEPTER_OF_POWER', 3);
define('DR_TOKEN_THIEVES', 4);
define('DR_TOKEN_SCROLL', 5);
define('DR_TOKEN_RING_INVISIBILITY', 6);
define('DR_TOKEN_DRAGON_SCALES', 7);
define('DR_TOKEN_ELIXIR', 8);
define('DR_TOKEN_DRAGON_BAIT', 9);
define('DR_TOKEN_TOWN_PORTAL', 10);

/*
 * LOCATION (Zones)
 */

define('DR_ZONE_PARTY', 'party');
define('DR_ZONE_DUNGEON', 'dungeon');
define('DR_ZONE_GRAVEYARD', 'graveyard');
define('DR_ZONE_DRAGON_LAIR', 'dragon_lair');
define('DR_ZONE_PLAY', 'play');
define('DR_ZONE_INVENTORY', 'inventory');
define('DR_ZONE_BOX', 'box');
define('DR_ZONE_HERO', 'hero');
define('DR_ZONE_DRAFT', 'draft');

/*
 * Game statistics constants
 */

define('DR_STAT_XP_LEVEL_ID', 11);
define('DR_STAT_XP_DRAGON_ID', 12);
define('DR_STAT_XP_TREASURE_ID', 13);
define('DR_STAT_XP_DRAGON_SCALE_ID', 14);
define('DR_STAT_XP_TOWN_PORTAL_ID', 15);


define('DR_STAT_XP_LEVEL', "player_xp_level");
define('DR_STAT_XP_DRAGON', "player_xp_dragon");
define('DR_STAT_XP_TREASURE', "player_xp_treasure");
define('DR_STAT_XP_DRAGON_SCALE', "player_xp_dragon_scale");
define('DR_STAT_XP_TOWN_PORTAL', "player_xp_town_portal");

define('DR_STAT_NBR_DRAGON_KILL_ID', 21);
define('DR_STAT_NBR_DRAGON_KILL', "player_nbr_dragon");

define('DR_STAT_NBR_TREASURE_OPEN_ID', 22);
define('DR_STAT_NBR_TREASURE_OPEN', "player_nbr_treasures");


/*
 * States
 */

define('DR_STATE_GAME_OPTION', 2);
define('DR_STATE_RANDOM_HERO', 3);
define('DR_STATE_SELECT_HERO', 5);
define('DR_STATE_NEXT_PLAYER_HERO', 6);
define('DR_STATE_SETUP_MIRROR_GAME', 7);

define('DR_STATE_INIT_PLAYER_TURN', 10);
define('DR_STATE_FORMING_PARTY', 12);
define('DR_STATE_POST_FORMING_PARTY_MERCENARY', 13);
define('DR_STATE_POST_FORMING_PARTY_SCOUT', 14);
define('DR_STATE_POST_FORMING_PARTY_LOEG_YLLAVYRE', 16);

define('DR_STATE_DUNGEON_ROLL', 15);

define('DR_STATE_PRE_MONSTER_PHASE', 19);
define('DR_STATE_MONSTER_PHASE', 20);

define('DR_STATE_PRE_LOOT_PHASE', 29);
define('DR_STATE_LOOT_PHASE', 30);

define('DR_STATE_CHOOSE_DIE', 35);

define('DR_STATE_PRE_DRAGON_PHASE', 39);
define('DR_STATE_DRAGON_PHASE', 40);

define('DR_STATE_PRE_REGROUP_PHASE', 49);
define('DR_STATE_REGROUP_PHASE', 50);

define('DR_STATE_PRE_NEXT_PLAYER', 59);
define('DR_STATE_NEXT_PLAYER', 60);

define('DR_STATE_DISCARD_TREASURE', 70);
define('DR_STATE_ULTIMATE_GUILD_LEADER', 71);
define('DR_STATE_SELECTION_DICE', 72);
define('DR_STATE_ULTIMATE_SZOPIN', 73);
define('DR_STATE_ULTIMATE_TRISTAN', 74);
define('DR_STATE_SPECIALTY_ALEXANDRA', 75);

define('DR_STATE_FINAL_SCORING', 98);

/*
 * Game global variables 
 */
define('DR_GL_CURRENT_TURN_ID', 10);
define('DR_GL_CURRENT_TURN', "table_current_turn");

define('DR_GL_CURRENT_LEVEL_ID', 11);
define('DR_GL_CURRENT_LEVEL', "player_current_level");

define('DR_GL_MAX_TURN_ID', 12);
define('DR_GL_MAX_TURN', 'table_max_turn');

define('DR_GL_HERO_ACTIVATED_ID', 13);
define('DR_GL_HERO_ACTIVATED', 'hero_activated');

define('DR_GL_CHOOSE_DR_DIE_COUNT_ID', 14);
define('DR_GL_CHOOSE_DR_DIE_COUNT', 'choose_die_count');

define('DR_GL_CHOOSE_DR_DIE_STTE_ID', 15);
define('DR_GL_CHOOSE_DR_DIE_STATE', 'choose_die_state');

define('DR_GL_SPECIALTY_ONCE_PER_LEVEL_ID', 16);
define('DR_GL_SPECIALTY_ONCE_PER_LEVEL', 'specialty_per_level');

define('DR_GL_DRAGON_KILLED_THIS_TURN_ID', 17);
define('DR_GL_DRAGON_KILLED_THIS_TURN', 'dragon_killed_this_turn');

define('DR_GL_BERSERKER_ULTIMATE_ID', 18);
define('DR_GL_BERSERKER_ULTIMATE', 'berserker_ultimate');

define('DR_GV_GAME_OPTION_ID', 100);
define('DR_GV_GAME_OPTION', 'gameOption');

define('DR_GV_GAME_EXPANSION_ID', 101);
define('DR_GV_GAME_EXPANSION', 'Expansion');

define('DR_GV_GAME_MIRROR_ID', 102);
define('DR_GV_GAME_MIRROR', 'Mirror match');

/*
 * Game options 
 */

define('DR_GAME_OPTION_RANDOM_HERO', 1);
define('DR_GAME_OPTION_SELECT_HERO', 2);
define('DR_GAME_OPTION_NO_HERO', 3);

define('DR_GAME_EXPANSION_BASE', 1);
define('DR_GAME_EXPANSION_PACK_1', 2);
define('DR_GAME_EXPANSION_PACK_2', 3);
define('DR_GAME_EXPANSION_KICKSTARTER', 99);
define('DR_GAME_EXPANSION_ALL', 110);

define('DR_GAME_MIRROR_NO', 1);
define('DR_GAME_MIRROR_YES', 2);

