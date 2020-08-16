<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * DungeonRollMg implementation : © Martin Goulet <martin.goulet@live.ca>
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * DungeonRollMg game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */

require_once("modules/constants.inc.php");

$this->items = array(
    array(
        'type' => TYPE_PARTY_DIE,
        'value' => 0,
        'name' => '',
        'number' => 7
    ),
    array(
        'type' => TYPE_DUNGEON_DIE,
        'value' => 0,
        'name' => '',
        'number' => 7
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_VORPAL_SWORD,
        'name' => 'VorpalSword',
        'number' => 3
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_TALISMAN,
        'name' => 'Talisman',
        'number' => 3
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_SCEPTER_OF_POWER,
        'name' => 'ScepterOfPower',
        'number' => 3
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_THIEVES,
        'name' => 'Thieves',
        'number' => 3
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_SCROLL,
        'name' => 'Scroll',
        'number' => 3
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_RING_INVISIBILITY,
        'name' => 'RingOfInvisibility',
        'number' => 4
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_DRAGON_SCALES,
        'name' => 'DragonScales',
        'number' => 6
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_POTION,
        'name' => 'Potion',
        'number' => 3
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_DRAGON_BAIT,
        'name' => 'DragonBait',
        'number' => 4
    ),
    array(
        'type' => TYPE_TREASURE_TOKEN,
        'value' => TOKEN_TOWN_PORTAL,
        'name' => 'TownPortal',
        'number' => 4
    ),
);

$this->items_party_dice = array(
    TYPE_PARTY_DIE.'_'.DIE_MAGE => array(
        'name' => clienttranslate("Mage"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 1,
        'weight' => 11,
        'small_icon' => 'dice_mage',
    ),
    TYPE_PARTY_DIE.'_'.DIE_CLERIC => array(
        'name' => clienttranslate("Cleric"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 2,
        'weight' => 12,
        'small_icon' => 'dice_cleric',
    ),
    TYPE_PARTY_DIE.'_'.DIE_FIGHTER => array(
        'name' => clienttranslate("Fighter"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 3,
        'weight' => 13,
        'small_icon' => 'dice_fighter',
    ),
    TYPE_PARTY_DIE.'_'.DIE_THIEF => array(
        'name' => clienttranslate("Thief"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 4,
        'weight' => 14,
        'small_icon' => 'dice_thief',
    ),
    TYPE_PARTY_DIE.'_'.DIE_CHAMPION => array(
        'name' => clienttranslate("Champion"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 5,
        'weight' => 15,
        'small_icon' => 'dice_champion',
    ),
    TYPE_PARTY_DIE.'_'.DIE_SCROLL => array(
        'name' => clienttranslate("Scroll"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 0,
        'weight' => 16,
        'small_icon' => 'dice_scroll',
    ),
);

$this->items_dungeon_dice = array(
    TYPE_DUNGEON_DIE.'_'.DIE_GOBLIN => array(
        'name' => clienttranslate("Goblin"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 5,
        'weight' => 1,
    ),
    TYPE_DUNGEON_DIE.'_'.DIE_OOZE => array(
        'name' => clienttranslate("Ooze"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 2,
        'weight' => 2,
    ),
    TYPE_DUNGEON_DIE.'_'.DIE_SKELETON => array(
        'name' => clienttranslate("Skeleton"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 3,
        'weight' => 3,
    ),
    TYPE_DUNGEON_DIE.'_'.DIE_CHEST => array(
        'name' => clienttranslate("Chest"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 4,
        'weight' => 4,
    ),
    TYPE_DUNGEON_DIE.'_'.DIE_POTION => array(
        'name' => clienttranslate("Potion"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 1,
        'weight' => 5,
    ),
    TYPE_DUNGEON_DIE.'_'.DIE_DRAGON => array(
        'name' => clienttranslate("Dragon"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 0,
        'weight' => 6,
    ),
);

$this->items_treasure_tokens = array(
    TYPE_TREASURE_TOKEN.'_'.TOKEN_DRAGON_BAIT => array(
        'name' => clienttranslate("Dragon bait"),
        'image_file' => 'img/treasures.png',
        'image_index' => 0,
        'weight' => 29,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_RING_INVISIBILITY => array(
        'name' => clienttranslate("Ring of invisibility"),
        'image_file' => 'img/treasures.png',
        'image_index' => 1,
        'weight' => 28,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_TOWN_PORTAL => array(
        'name' => clienttranslate("Town portal"),
        'image_file' => 'img/treasures.png',
        'image_index' => 2,
        'weight' => 27,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_DRAGON_SCALES => array(
        'name' => clienttranslate("Dragon scale"),
        'image_file' => 'img/treasures.png',
        'image_index' => 3,
        'weight' => 30,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_SCROLL => array(
        'name' => clienttranslate("Scroll"),
        'image_file' => 'img/treasures.png',
        'image_index' => 4,
        'weight' => 25,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_POTION => array(
        'name' => clienttranslate("Potion"),
        'image_file' => 'img/treasures.png',
        'image_index' => 5,
        'weight' => 26,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_VORPAL_SWORD => array(
        'name' => clienttranslate("Vorpal sword"),
        'image_file' => 'img/treasures.png',
        'image_index' => 6,
        'weight' => 21,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_TALISMAN => array(
        'name' => clienttranslate("Talisman"),
        'image_file' => 'img/treasures.png',
        'image_index' => 7,
        'weight' => 22,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_SCEPTER_OF_POWER => array(
        'name' => clienttranslate("Scepter of power"),
        'image_file' => 'img/treasures.png',
        'image_index' => 8,
        'weight' => 23,
    ),
    TYPE_TREASURE_TOKEN.'_'.TOKEN_THIEVES => array(
        'name' => clienttranslate("Thieves"),
        'image_file' => 'img/treasures.png',
        'image_index' => 9,
        'weight' => 24,
    ),
);

$this->card_types = array(

    "4_1" => array(
        'name' => clienttranslate("Spellsword"),
        'specialty' => clienttranslate("Fighters may be used as Mages and Mages may be used as Fighters"),
        'ultimate' => clienttranslate("Spellsword may be used as a Fighter or a Mage"),
        'imageindex' => 1,
        'heroclass' => "DRSpellsword",
        'commandText' => "",
        'commands' => array(
            1 => clienttranslate('Spellsword : Use as a Fighter'),
            2 => clienttranslate('Spellsword : Use as a Mage'),
        )
    ),

    "5_1" => array(
        'name' => clienttranslate("Battlemage"),
        'specialty' => clienttranslate("Fighters may be used as Mages and Mages may be used as Fighters"),
        'ultimate' => clienttranslate("Discard all Monsters, Chests, Potions, and dice in the Dragon's Lair"),
        'imageindex' => 2,
        'heroclass' => "DRBattlemage",
        'commandText' => clienttranslate("Battlemage : Discard all dungeon dice"),
    ),

    "4_2" => array(
        'name' => clienttranslate("Mercenary"),
        'specialty' => clienttranslate("When Forming the Party you may re-roll any number of Party dice"),
        'ultimate' => clienttranslate("Defeat any 2 Monsters"),
        'imageindex' => 3,
        'heroclass' => "DRMercenary",
        'commandText' => clienttranslate("Mercenary : Defeat any 2 Monsters"),
    ),

    "5_2" => array(
        'name' => clienttranslate("Commander"),
        'specialty' => clienttranslate("Fighters defeat 1 extra Monster of any type"),
        'ultimate' => clienttranslate("Re-roll any number of Party and Dungeon dice"),
        'imageindex' => 4,
        'heroclass' => "DRCommander",
        'commandText' => clienttranslate("Commander : Re-roll Party and Dungeon dice"),
    ),

    "4_3" => array(
        'name' => clienttranslate("Knight"),
        'specialty' => clienttranslate("When Forming the Party, all Scrolls become Champions"),
        'ultimate' => clienttranslate("Transform all Monsters to Dragon faces and move them to the Dragon's Lair"),
        'imageindex' => 5,
        'heroclass' => "DRKnight",
        'commandText' => clienttranslate("Knight : Transform all Monsters to Dragon faces"),
    ),

    "5_3" => array(
        'name' => clienttranslate("Dragon Slayer"),
        'specialty' => clienttranslate("When Forming the Party, all Scrolls become Champions. Use 2 different companions to defeat Dragons (instead of 3)"),
        'ultimate' => clienttranslate("Transform all Monsters to Dragon faces and move them to the Dragon's Lair"),
        'imageindex' => 6,
        'heroclass' => "DRDragonSlayer",
        'commandText' => clienttranslate("Dragon Slayer : Transform all Monsters to Dragon faces"),
    ),

    "4_4" => array(
        'name' => clienttranslate("Half-Goblin"),
        'specialty' => clienttranslate("You may open Chests and quaff Potions at any time during the Monsters Phase"),
        'ultimate' => clienttranslate("Transform 1 Goblin into a Thief. Discard it during the next Regroup Phase"),
        'imageindex' => 7,
        'heroclass' => "DRHalfGoblin",
        'commandText' => clienttranslate("Half-Goblin : Transform 1 Goblin into a Thief"),
    ),

    "5_4" => array(
        'name' => clienttranslate("Chieftain"),
        'specialty' => clienttranslate("You may open Chests and quaff Potions at any time during the Monsters Phase"),
        'ultimate' => clienttranslate("Transform 2 Goblins into Thieves. Discard them during the next Regroup Phase"),
        'imageindex' => 8,
        'heroclass' => "DRChieftain",
        'commandText' => clienttranslate("Chieftain : Transform 2 Goblins into Thieves"),
    ),

    "4_5" => array(
        'name' => clienttranslate("Minstrel"),
        'specialty' => clienttranslate("Thieves may be used as Mages and Mages may be used as Thieves"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'imageindex' => 9,
        'heroclass' => "DRMinstrel",
        'commandText' => clienttranslate("Minstrel : Discard all dice from the Dragon's Lair"),
    ),

    "5_5" => array(
        'name' => clienttranslate("Bard"),
        'specialty' => clienttranslate("Thieves may be used as Mages and Mages may be used as Thieves. Champions defeat 1 extra monster"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'imageindex' => 10,
        'heroclass' => "DRBard",
        'commandText' => clienttranslate("Bard : Discard all dice from the Dragon's Lair"),
    ),

    "4_6" => array(
        'name' => clienttranslate("Enchantress"),
        'specialty' => clienttranslate("Scrolls may be used as any Companion"),
        'ultimate' => clienttranslate("Transform 1 Monster into a potion"),
        'imageindex' => 11,
        'heroclass' => "DREnchantress",
        'commandText' => clienttranslate("Enchantress : Transform 1 Monster into a potion"),
    ),

    "5_6" => array(
        'name' => clienttranslate("Beguiler"),
        'specialty' => clienttranslate("Scrolls may be used as any Companion"),
        'ultimate' => clienttranslate("Transform 2 Monsters into 1 potion"),
        'imageindex' => 12,
        'heroclass' => "DRBeguiler",
        'commandText' => clienttranslate("Beguiler : Transform 2 Monsters into 1 potion"),
    ),

    "4_7" => array(
        'name' => clienttranslate("Occultist"),
        'specialty' => clienttranslate("Clerics may be used as Mages and Mages may be used as Clerics"),
        'ultimate' => clienttranslate("Transform 1 Skeleton into a Fighter. Discard it during the next Regroup Phase"),
        'imageindex' => 13,
        'heroclass' => "DROccultist",
        'commandText' => clienttranslate("Occultist : Transform 1 Skeleton into a Fighter"),
    ),

    "5_7" => array(
        'name' => clienttranslate("Necromancer"),
        'specialty' => clienttranslate("Clerics may be used as Mages and Mages may be used as Clerics"),
        'ultimate' => clienttranslate("Transform 2 Skeleton into a Fighter. Discard it during the next Regroup Phase"),
        'imageindex' => 14,
        'heroclass' => "DRNecromancer",
        'commandText' => clienttranslate("Necromancer : Transform 2 Skeletons into a Fighter"),
    ),

    "4_8" => array(
        'name' => clienttranslate("Crusader"),
        'specialty' => clienttranslate("Fighters may be used as Clerics and Clerics may be used as Fighters"),
        'ultimate' => clienttranslate("Crusader may be used as a Cleric or a Fighter"),
        'imageindex' => 15,
        'heroclass' => "DRCrusader",
        'commandText' => "",
        'commands' => array(
            1 => clienttranslate('Crusader : Use as a Fighter'),
            2 => clienttranslate('Crusader : Use as a Cleric'),
        )
    ),

    "5_8" => array(
        'name' => clienttranslate("Paladin"),
        'specialty' => clienttranslate("Fighters may be used as Clerics and Clerics may be used as Fighters"),
        'ultimate' => clienttranslate("Discard 1 Treasure Token to defeat all Monsters, open all Chests, quaff all Potions and discard all dice in the Dragon's Lair"),
        'imageindex' => 16,
        'heroclass' => "DRPaladin",
        'commandText' => clienttranslate("Paladin : Defeat Monsters, open chests, quaff potions and discard Dragons"),
    ),
);

$this->command_infos = array(

    1 => array(
        'name' => 'useScroll',
        'text' => clienttranslate('Use scroll'),
        'php_class' => 'DRCommandUseScroll'
    ),

    3 => array(
        'name' => 'openChest',
        'text' => clienttranslate('Open Chest'),
        'php_class' => 'DRCommandOpenChest'
    ),

    4 => array(
        'name' => 'dragonBait',
        'text' => clienttranslate('Use dragon bait'),
        'php_class' => 'DRCommandDragonBait'
    ),

    5 => array(
        'name' => 'townPortal',
        'text' => clienttranslate('Use town portal'),
        'php_class' => 'DRCommandTownPortal'
    ),

    6 => array(
        'name' => 'ringInvisibility',
        'text' => clienttranslate('Use ring of invisibility'),
        'php_class' => 'DRCommandRingInvisibility'
    ),

    7 => array(
        'name' => 'seekGlory',
        'text' => clienttranslate('Seek glory'),
        'php_class' => 'DRCommandSeekGlory'
    ),

    11 => array(
        'name' => 'fightDragon',
        'text' => clienttranslate('Fight dragons'),
        'php_class' => 'DRCommandFightDragon'
    ),

    12 => array(
        'name' => 'fightMonster',
        'text' => clienttranslate('Fight monsters'),
        'php_class' => 'DRCommandFightMonster'
    ),


    14 => array(
        'name' => 'quaffPotion',
        'text' => clienttranslate('Quaff potion'),
        'php_class' => 'DRCommandQuaffPotion'
    ),


    15 => array(
        'name' => 'usePotion',
        'text' => clienttranslate('Use potion'),
        'php_class' => 'DRCommandUsePotion'
    ),

    16 => array(
        'name' => 'rollFormingPartyPhase',
        'text' => clienttranslate('Roll dice'),
        'php_class' => 'DRCommandRollFormingPartyPhase'
    ),



    50 => array(
        'name' => 'endFormingPartyPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndFormingPartyPhase'
    ),


    51 => array(
        'name' => 'endMonsterPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndMonsterPhase'
    ),

    52 => array(
        'name' => 'endLootPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndLootPhase'
    ),

    60 => array(
        'name' => 'retireTavern',
        'text' => clienttranslate('Retire to the tavern'),
        'button_color' => "red",
        'php_class' => 'DRCommandRetireTavern'
    ),

    61 => array(
        'name' => 'fleeDungeon',
        'text' => clienttranslate('Flee the dungeon'),
        'button_color' => "red",
        'confirmation' => clienttranslate("Are you sure to flee the dungeon?"),
        'php_class' => 'DRCommandFleeDungeon'
    ),

    70 => array(
        'name' => 'heroUltimate',
        'text' => clienttranslate('Hero Ultimate'),
        'php_class' => 'DRCommandHeroUltimate'
    ),

);