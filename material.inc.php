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
 * material.inc.php
 *
 * DungeonRoll game material description
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
        'value' => TOKEN_ELIXIR,
        'name' => 'Elixir',
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
    TYPE_PARTY_DIE . '_' . DIE_MAGE => array(
        'name' => clienttranslate("Mage"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 1,
        'weight' => 11,
        'small_icon' => 'dice_mage',
        'tooltip' => array(
            clienttranslate("May be used to defeat 1 Goblin, 1 Skeleton, or any number of Oozes"),
            clienttranslate("May be used to open 1 Chest or quaff any number of Potions"),
        )
    ),
    TYPE_PARTY_DIE . '_' . DIE_CLERIC => array(
        'name' => clienttranslate("Cleric"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 2,
        'weight' => 12,
        'small_icon' => 'dice_cleric',
        'tooltip' => array(
            clienttranslate("May be used to defeat 1 Goblin, 1 Ooze, or any number of Skeletons"),
            clienttranslate("May be used to open 1 Chest or quaff any number of Potions"),
        )
    ),
    TYPE_PARTY_DIE . '_' . DIE_FIGHTER => array(
        'name' => clienttranslate("Fighter"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 3,
        'weight' => 13,
        'small_icon' => 'dice_fighter',
        'tooltip' => array(
            clienttranslate("May be used to defeat 1 Skeleton, 1 Ooze, or any number of Goblins"),
            clienttranslate("May be used to open 1 Chest or quaff any number of Potions"),
        )
    ),
    TYPE_PARTY_DIE . '_' . DIE_THIEF => array(
        'name' => clienttranslate("Thief"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 4,
        'weight' => 14,
        'small_icon' => 'dice_thief',
        'tooltip' => array(
            clienttranslate("May be used to defeat 1 Goblin, 1 Skeleton or 1 Ooze"),
            clienttranslate("May be used to open any number of Chests or quaff any number of Potions"),
        )
    ),
    TYPE_PARTY_DIE . '_' . DIE_CHAMPION => array(
        'name' => clienttranslate("Champion"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 5,
        'weight' => 15,
        'small_icon' => 'dice_champion',
        'tooltip' => array(
            clienttranslate("May be used to defeat any number of Goblins, any number of Skeletons or any number of Oozes"),
            clienttranslate("May be used to open any number of Chests or quaff any number of Potions"),
        )
    ),
    TYPE_PARTY_DIE . '_' . DIE_SCROLL => array(
        'name' => clienttranslate("Scroll"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 0,
        'weight' => 16,
        'small_icon' => 'dice_scroll',
        'tooltip' => array(
            clienttranslate("Scroll may be used to re-roll any number of Dungeon dice and Party dice"),
            clienttranslate("Scroll may be used to quaff any number of Potions"),
            clienttranslate("Scroll is not a Companion so you cannot use it to defeat Monster or Dragon"),
        )
    ),
);

$this->items_dungeon_dice = array(
    TYPE_DUNGEON_DIE . '_' . DIE_GOBLIN => array(
        'name' => clienttranslate("Goblin"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 5,
        'weight' => 1,
        'tooltip' => array(
            clienttranslate("One Fighter or Champion may be used to defeat any number Goblins"),
            clienttranslate("Any other Companion may be used to defeat 1 Goblin"),
        )
    ),
    TYPE_DUNGEON_DIE . '_' . DIE_OOZE => array(
        'name' => clienttranslate("Ooze"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 2,
        'weight' => 2,
        'tooltip' => array(
            clienttranslate("One Mage or Champion may be used to defeat any number Oozes"),
            clienttranslate("Any other Companion may be used to defeat 1 Ooze"),
        )
    ),
    TYPE_DUNGEON_DIE . '_' . DIE_SKELETON => array(
        'name' => clienttranslate("Skeleton"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 3,
        'weight' => 3,
        'tooltip' => array(
            clienttranslate("One Cleric or Champion may be used to defeat any number Skeletons"),
            clienttranslate("Any other Companion may be used to defeat 1 Skeleton"),
        )
    ),
    TYPE_DUNGEON_DIE . '_' . DIE_CHEST => array(
        'name' => clienttranslate("Chest"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 4,
        'weight' => 4,
        'tooltip' => array(
            clienttranslate("One Thief or Champion may be used to open any number of Chests in the level"),
            clienttranslate("Any other Companion may be used to open 1 Chest"),
            clienttranslate("For each Chest opened, you get one Treasure token"),
            clienttranslate("In the rare case that no Treasure token remain in the box, you receive 1 Experience instead"),
        )
    ),
    TYPE_DUNGEON_DIE . '_' . DIE_POTION => array(
        'name' => clienttranslate("Potion"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 1,
        'weight' => 5,
        'tooltip' => array(
            clienttranslate("Any Party die (including Scrolls) can be used to Quaff any number of Potions"),
            clienttranslate("For each Potion quaffed, you take 1 Party die from the Graveyard and adds it to her active party, choosing its face"),
            clienttranslate("Unused Potion are returned to the available pool before moving onto the Dragon phase"),
        )
    ),
    TYPE_DUNGEON_DIE . '_' . DIE_DRAGON => array(
        'name' => clienttranslate("Dragon"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 0,
        'weight' => 6,
        'tooltip' => array(
            clienttranslate("Use three different types of Companions to defeat the Dragon. Treasure that act like a Companion may be used in this way"),
            clienttranslate("After defeating the Dragon, you get 1 Treasure Token from the box and earn 1 Experience"),
        )
    ),
);

$this->items_treasure_tokens = array(
    TYPE_TREASURE_TOKEN . '_' . TOKEN_DRAGON_BAIT => array(
        'name' => clienttranslate("Dragon bait"),
        'image_file' => 'img/treasures.png',
        'image_index' => 0,
        'weight' => 29,
        'tooltip' => array(
            clienttranslate("Transform all monsters into the Dragon faces and move those dice into the Dragon's Lair"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_RING_INVISIBILITY => array(
        'name' => clienttranslate("Ring of invisibility"),
        'image_file' => 'img/treasures.png',
        'image_index' => 1,
        'weight' => 28,
        'tooltip' => array(
            clienttranslate("Return all Dungeon dice from the Dragon's Lair to the active supply of Dungeon dice"),
            clienttranslate("This does not count as Defeating the Dragon - do not collect Experience or get Treasure"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_TOWN_PORTAL => array(
        'name' => clienttranslate("Town portal"),
        'image_file' => 'img/treasures.png',
        'image_index' => 2,
        'weight' => 27,
        'tooltip' => array(
            clienttranslate("Collect Experience equal to the Level. The delve is over. If unused, Town Portal is worth 2 Experience at the end of the game instead of the usual 1"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_DRAGON_SCALES => array(
        'name' => clienttranslate("Dragon scales"),
        'image_file' => 'img/treasures.png',
        'image_index' => 3,
        'weight' => 30,
        'tooltip' => array(
            clienttranslate("At the end of the game, collect 2 additional Experiences for each pair of Dragon Scales you possess"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_SCROLL => array(
        'name' => clienttranslate("Scroll"),
        'image_file' => 'img/treasures.png',
        'image_index' => 4,
        'weight' => 25,
        'tooltip' => array(
            clienttranslate("Use as one Scroll die face"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_ELIXIR => array(
        'name' => clienttranslate("Elixir"),
        'image_file' => 'img/treasures.png',
        'image_index' => 5,
        'weight' => 26,
        'tooltip' => array(
            clienttranslate("Revive 1 Party die (return it from the Graveyard to your active party) and choose its face"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_VORPAL_SWORD => array(
        'name' => clienttranslate("Vorpal sword"),
        'image_file' => 'img/treasures.png',
        'image_index' => 6,
        'weight' => 21,
        'tooltip' => array(
            clienttranslate("Use as one Fighter die face"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_TALISMAN => array(
        'name' => clienttranslate("Talisman"),
        'image_file' => 'img/treasures.png',
        'image_index' => 7,
        'weight' => 22,
        'tooltip' => array(
            clienttranslate("Use as one Cleric die face"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_SCEPTER_OF_POWER => array(
        'name' => clienttranslate("Scepter of power"),
        'image_file' => 'img/treasures.png',
        'image_index' => 8,
        'weight' => 23,
        'tooltip' => array(
            clienttranslate("Use as one Mage die face"),
        )
    ),
    TYPE_TREASURE_TOKEN . '_' . TOKEN_THIEVES => array(
        'name' => clienttranslate("Thieves"),
        'image_file' => 'img/treasures.png',
        'image_index' => 9,
        'weight' => 24,
        'tooltip' => array(
            clienttranslate("Use as one Thief die face"),
        )
    ),
);

$this->card_types = array(

    "4_1" => array(
        'name' => clienttranslate("Spellsword"),
        'specialty' => clienttranslate("Fighters may be used as Mages and Mages may be used as Fighters"),
        'ultimate' => clienttranslate("Spellsword may be used as a Fighter or a Mage"),
        'imageindex' => 1,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
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
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRBattlemage",
        'commandText' => clienttranslate("Battlemage : Discard all dungeon dice"),
    ),

    "4_2" => array(
        'name' => clienttranslate("Mercenary"),
        'specialty' => clienttranslate("When Forming the Party you may re-roll any number of Party dice"),
        'ultimate' => clienttranslate("Defeat up to 2 Monsters"),
        'imageindex' => 3,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRMercenary",
        'commandText' => clienttranslate("Mercenary : Defeat up to 2 Monsters"),
    ),

    "5_2" => array(
        'name' => clienttranslate("Commander"),
        'specialty' => clienttranslate("Fighters defeat 1 extra Monster of any type"),
        'ultimate' => clienttranslate("Re-roll any number of Party and Dungeon dice"),
        'imageindex' => 4,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRCommander",
        'commandText' => clienttranslate("Commander : Re-roll Party and Dungeon dice"),
    ),

    "4_3" => array(
        'name' => clienttranslate("Knight"),
        'specialty' => clienttranslate("When Forming the Party, all Scrolls become Champions"),
        'ultimate' => clienttranslate("Transform all Monsters to Dragon faces and move them to the Dragon's Lair"),
        'imageindex' => 5,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRKnight",
        'commandText' => clienttranslate("Knight : Transform all Monsters to Dragon faces"),
    ),

    "5_3" => array(
        'name' => clienttranslate("Dragon Slayer"),
        'specialty' => clienttranslate("When Forming the Party, all Scrolls become Champions. Use 2 different companions to defeat the Dragon (instead of 3)"),
        'ultimate' => clienttranslate("Transform all Monsters to Dragon faces and move them to the Dragon's Lair"),
        'imageindex' => 6,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRDragonSlayer",
        'commandText' => clienttranslate("Dragon Slayer : Transform all Monsters to Dragon faces"),
    ),

    "4_4" => array(
        'name' => clienttranslate("Half-Goblin"),
        'specialty' => clienttranslate("You may open Chests and quaff Potions at any time during the Monsters Phase"),
        'ultimate' => clienttranslate("Transform 1 Goblin into a Thief. Discard it during the next Regroup Phase"),
        'imageindex' => 7,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRHalfGoblin",
        'commandText' => clienttranslate("Half-Goblin : Transform 1 Goblin into a Thief"),
    ),

    "5_4" => array(
        'name' => clienttranslate("Chieftain"),
        'specialty' => clienttranslate("You may open Chests and quaff Potions at any time during the Monsters Phase"),
        'ultimate' => clienttranslate("Transform up to 2 Goblins into Thieves. Discard them during the next Regroup Phase"),
        'imageindex' => 8,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRChieftain",
        'commandText' => clienttranslate("Chieftain : Transform up to 2 Goblins into Thieves"),
    ),

    "4_5" => array(
        'name' => clienttranslate("Minstrel"),
        'specialty' => clienttranslate("Thieves may be used as Mages and Mages may be used as Thieves"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'imageindex' => 9,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRMinstrel",
        'commandText' => clienttranslate("Minstrel : Discard all Dragon dice"),
    ),

    "5_5" => array(
        'name' => clienttranslate("Bard"),
        'specialty' => clienttranslate("Thieves may be used as Mages and Mages may be used as Thieves. Champions defeat 1 extra monster"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'imageindex' => 10,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRBard",
        'commandText' => clienttranslate("Bard : Discard all Dragon dice"),
    ),

    "4_6" => array(
        'name' => clienttranslate("Enchantress"),
        'specialty' => clienttranslate("Scrolls may be used as any Companion"),
        'ultimate' => clienttranslate("Transform 1 Monster into 1 Potion"),
        'imageindex' => 11,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DREnchantress",
        'commandText' => clienttranslate("Enchantress : Transform 1 Monster into 1 Potion"),
    ),

    "5_6" => array(
        'name' => clienttranslate("Beguiler"),
        'specialty' => clienttranslate("Scrolls may be used as any Companion"),
        'ultimate' => clienttranslate("Transform up to 2 Monsters into 1 Potion"),
        'imageindex' => 12,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRBeguiler",
        'commandText' => clienttranslate("Beguiler : Transform up to 2 Monsters into 1 Potion"),
    ),

    "4_7" => array(
        'name' => clienttranslate("Occultist"),
        'specialty' => clienttranslate("Clerics may be used as Mages and Mages may be used as Clerics"),
        'ultimate' => clienttranslate("Transform 1 Skeleton into a Fighter. Discard it during the next Regroup Phase"),
        'imageindex' => 13,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DROccultist",
        'commandText' => clienttranslate("Occultist : Transform 1 Skeleton into a Fighter"),
    ),

    "5_7" => array(
        'name' => clienttranslate("Necromancer"),
        'specialty' => clienttranslate("Clerics may be used as Mages and Mages may be used as Clerics"),
        'ultimate' => clienttranslate("Transform up to 2 Skeletons into Fighters. Discard it during the next Regroup Phase"),
        'imageindex' => 14,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRNecromancer",
        'commandText' => clienttranslate("Necromancer : Transform up to 2 Skeletons into Fighters"),
    ),

    "4_8" => array(
        'name' => clienttranslate("Crusader"),
        'specialty' => clienttranslate("Fighters may be used as Clerics and Clerics may be used as Fighters"),
        'ultimate' => clienttranslate("Crusader may be used as a Cleric or a Fighter"),
        'imageindex' => 15,
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
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
        'expansions' => array(GAME_EXPANSION_BASE, GAME_EXPANSION_BASE_PACK_1),
        'heroclass' => "DRPaladin",
        'commandText' => clienttranslate("Paladin : Defeat Monsters, open Chests, quaff Potions and discard Dragon dice"),
    ),
);

$this->command_infos = array(


    1 => array(
        'name' => 'fightMonster',
        'text' => clienttranslate('Fight Monsters'),
        'php_class' => 'DRCommandFightMonster',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    5 => array(
        'name' => 'openChest',
        'text' => clienttranslate('Open Chest'),
        'php_class' => 'DRCommandOpenChest',
        'always_visible' => true,
        'html_zone' => 'zone_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => 'checkNonSelectedChest',
                'confirmation' => clienttranslate("Are you sure you not want to open all Chests?")
            )
        )
    ),

    6 => array(
        'name' => 'quaffPotion',
        'text' => clienttranslate('Quaff Potion'),
        'php_class' => 'DRCommandQuaffPotion',
        'always_visible' => true,
        'html_zone' => 'zone_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => 'checkNonSelectedPotion',
                'confirmation' => clienttranslate("Are you sure you not want to quaff all Potions?")
            )
        )
    ),

    10 => array(
        'name' => 'fightDragon',
        'text' => clienttranslate('Fight Dragon'),
        'php_class' => 'DRCommandFightDragon',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    13 => array(
        'name' => 'rollFormingPartyPhase',
        'text' => clienttranslate('Re-roll dice'),
        'php_class' => 'DRCommandRollFormingPartyPhase',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    15 => array(
        'name' => 'useScroll',
        'text' => clienttranslate('Use Scroll'),
        'php_class' => 'DRCommandUseScroll',
        'always_visible' => true,
        'html_zone' => 'zone_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => 'checkRerollPotion',
                'confirmation' => clienttranslate("Are you sure you want to re-roll Potion(s)?")
            ),
            array(
                'askConfirmation' => 'checkRerollDragon',
                'confirmation' => clienttranslate("Dragon dice cannot be re-rolled. Are you sure you want to make this action?")
            )
        )
    ),



    20 => array(
        'name' => 'dragonBait',
        'text' => clienttranslate('Use Dragon Bait'),
        'php_class' => 'DRCommandDragonBait',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    21 => array(
        'name' => 'townPortal',
        'text' => clienttranslate('Use Town Portal'),
        'php_class' => 'DRCommandTownPortal',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    22 => array(
        'name' => 'usePotion',
        'text' => clienttranslate('Use Elixir'),
        'php_class' => 'DRCommandUsePotion',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    23 => array(
        'name' => 'ringInvisibility',
        'text' => clienttranslate('Use Ring of Invisibility'),
        'php_class' => 'DRCommandRingInvisibility',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),


    50 => array(
        'name' => 'endFormingPartyPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndFormingPartyPhase',
        'always_visible' => true,
        'html_zone' => 'zone_phases_actions'
    ),


    51 => array(
        'name' => 'endMonsterPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndMonsterPhase',
        'always_visible' => true,
        'html_zone' => 'zone_phases_actions'
    ),

    52 => array(
        'name' => 'endLootPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndLootPhase',
        'always_visible' => true,
        'html_zone' => 'zone_phases_actions'
    ),

    53 => array(
        'name' => 'endDragonPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndDragonPhase',
        'always_visible' => true,
        'html_zone' => 'zone_phases_actions'
    ),

    54 => array(
        'name' => 'seekGlory',
        'text' => clienttranslate('Seek Glory'),
        'php_class' => 'DRCommandSeekGlory',
        'html_zone' => 'zone_phases_actions'
    ),

    60 => array(
        'name' => 'retireTavern',
        'text' => clienttranslate('Retire to the tavern'),
        'button_color' => "red",
        'php_class' => 'DRCommandRetireTavern',
        'html_zone' => 'zone_phases_actions'
    ),

    61 => array(
        'name' => 'fleeDungeon',
        'text' => clienttranslate('Flee the dungeon'),
        'button_color' => "red",
        'php_class' => 'DRCommandFleeDungeon',
        'html_zone' => 'zone_phases_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => "always",
                'confirmation' => clienttranslate("Are you sure to flee the dungeon?"),
            )
        )
    ),

    70 => array(
        'name' => 'heroUltimate',
        'text' => clienttranslate('Hero Ultimate'),
        'php_class' => 'DRCommandHeroUltimate',
        'html_zone' => 'zone_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => 'checkRerollDragonCommander',
                'confirmation' => clienttranslate("Dragon dice cannot be re-rolled. Are you sure you want to make this action?")
            )
        )
    ),

);

$this->phases = array(
    'monsterPhase' => array(
        'name' => clienttranslate("Monster phase"),
        'tooltip' => array(
            clienttranslate("You must defeat all monsters, <b>one fight at a time</b>"),
        ),
    ),
    'lootPhase' => array(
        'name' => clienttranslate("Loot phase"),
        'tooltip' => array(
            clienttranslate("You may open Chests and quaff Potions"),
        ),
    ),
    'dragonPhase' => array(
        'name' => clienttranslate("Dragon phase"),
        'tooltip' => array(
            clienttranslate("You must defeat the Dragon if 3 or more Dragon dice is present"),
        ),
    ),
    'regroupPhase' => array(
        'name' => clienttranslate("Regroup phase"),
        'tooltip' => array(
            clienttranslate("You must choose to continue or end the delve"),
        ),
    ),
);
