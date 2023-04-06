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
        'type' => DR_TYPE_PARTY_DIE,
        'value' => 0,
        'name' => '',
        'number' => 8
    ),
    array(
        'type' => DR_TYPE_DUNGEON_DIE,
        'value' => 0,
        'name' => '',
        'number' => 7
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_VORPAL_SWORD,
        'name' => 'VorpalSword',
        'number' => 6
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_TALISMAN,
        'name' => 'Talisman',
        'number' => 6
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_SCEPTER_OF_POWER,
        'name' => 'ScepterOfPower',
        'number' => 6
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_THIEVES,
        'name' => 'Thieves',
        'number' => 6
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_SCROLL,
        'name' => 'Scroll',
        'number' => 6
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_RING_INVISIBILITY,
        'name' => 'RingOfInvisibility',
        'number' => 8
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_DRAGON_SCALES,
        'name' => 'DragonScales',
        'number' => 12
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_ELIXIR,
        'name' => 'Elixir',
        'number' => 6
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_DRAGON_BAIT,
        'name' => 'DragonBait',
        'number' => 8
    ),
    array(
        'type' => DR_TYPE_TREASURE_TOKEN,
        'value' => DR_TOKEN_TOWN_PORTAL,
        'name' => 'TownPortal',
        'number' => 8
    ),
);

$this->items_party_dice = array(
    DR_TYPE_PARTY_DIE . '_' . DR_DIE_MAGE => array(
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
    DR_TYPE_PARTY_DIE . '_' . DR_DIE_CLERIC => array(
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
    DR_TYPE_PARTY_DIE . '_' . DR_DIE_FIGHTER => array(
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
    DR_TYPE_PARTY_DIE . '_' . DR_DIE_THIEF => array(
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
    DR_TYPE_PARTY_DIE . '_' . DR_DIE_CHAMPION => array(
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
    DR_TYPE_PARTY_DIE . '_' . DR_DIE_SCROLL => array(
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
    DR_TYPE_PARTY_DIE . '_' . DR_DIE_GENERIC_DRAGON => array(
        'name' => clienttranslate("Companion"),
        'image_file' => 'img/dice_party.jpg',
        'image_index' => 6,
        'weight' => 17,
        'tooltip' => array(
            clienttranslate("May be used to defeat 1 Goblin, 1 Skeleton or 1 Ooze"),
            clienttranslate("May be used to open 1 Chest or quaff any number of Potions"),
        )
    ),
);

$this->items_dungeon_dice = array(
    DR_TYPE_DUNGEON_DIE . '_' . DR_DIE_GOBLIN => array(
        'name' => clienttranslate("Goblin"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 5,
        'weight' => 1,
        'tooltip' => array(
            clienttranslate("One Fighter or Champion may be used to defeat any number Goblins"),
            clienttranslate("Any other Companion may be used to defeat 1 Goblin"),
        )
    ),
    DR_TYPE_DUNGEON_DIE . '_' . DR_DIE_OOZE => array(
        'name' => clienttranslate("Ooze"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 2,
        'weight' => 2,
        'tooltip' => array(
            clienttranslate("One Mage or Champion may be used to defeat any number Oozes"),
            clienttranslate("Any other Companion may be used to defeat 1 Ooze"),
        )
    ),
    DR_TYPE_DUNGEON_DIE . '_' . DR_DIE_SKELETON => array(
        'name' => clienttranslate("Skeleton"),
        'image_file' => 'img/dice_dungeon.jpg',
        'image_index' => 3,
        'weight' => 3,
        'tooltip' => array(
            clienttranslate("One Cleric or Champion may be used to defeat any number Skeletons"),
            clienttranslate("Any other Companion may be used to defeat 1 Skeleton"),
        )
    ),
    DR_TYPE_DUNGEON_DIE . '_' . DR_DIE_CHEST => array(
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
    DR_TYPE_DUNGEON_DIE . '_' . DR_DIE_POTION => array(
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
    DR_TYPE_DUNGEON_DIE . '_' . DR_DIE_DRAGON => array(
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
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_DRAGON_BAIT => array(
        'name' => clienttranslate("Dragon bait"),
        'image_file' => 'img/treasures.png',
        'image_index' => 0,
        'weight' => 29,
        'tooltip' => array(
            clienttranslate("Transform all monsters into the Dragon faces and move those dice into the Dragon's Lair"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_RING_INVISIBILITY => array(
        'name' => clienttranslate("Ring of invisibility"),
        'image_file' => 'img/treasures.png',
        'image_index' => 1,
        'weight' => 28,
        'tooltip' => array(
            clienttranslate("Return all Dungeon dice from the Dragon's Lair to the active supply of Dungeon dice"),
            clienttranslate("This does not count as Defeating the Dragon - do not collect Experience or get Treasure"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_TOWN_PORTAL => array(
        'name' => clienttranslate("Town Portal"),
        'image_file' => 'img/treasures.png',
        'image_index' => 2,
        'weight' => 27,
        'tooltip' => array(
            clienttranslate("Collect Experience equal to the Level. The delve is over. If unused, Town Portal is worth 2 Experience at the end of the game instead of the usual 1"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_DRAGON_SCALES => array(
        'name' => clienttranslate("Dragon scales"),
        'image_file' => 'img/treasures.png',
        'image_index' => 3,
        'weight' => 30,
        'tooltip' => array(
            clienttranslate("At the end of the game, collect 2 additional Experiences for each pair of Dragon Scales you possess"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_SCROLL => array(
        'name' => clienttranslate("Scroll"),
        'image_file' => 'img/treasures.png',
        'image_index' => 4,
        'weight' => 25,
        'tooltip' => array(
            clienttranslate("Use as one Scroll die face"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_ELIXIR => array(
        'name' => clienttranslate("Elixir"),
        'image_file' => 'img/treasures.png',
        'image_index' => 5,
        'weight' => 26,
        'tooltip' => array(
            clienttranslate("Revive 1 Party die (return it from the Graveyard to your active party) and choose its face"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_VORPAL_SWORD => array(
        'name' => clienttranslate("Vorpal sword"),
        'image_file' => 'img/treasures.png',
        'image_index' => 6,
        'weight' => 21,
        'tooltip' => array(
            clienttranslate("Use as one Fighter die face"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_TALISMAN => array(
        'name' => clienttranslate("Talisman"),
        'image_file' => 'img/treasures.png',
        'image_index' => 7,
        'weight' => 22,
        'tooltip' => array(
            clienttranslate("Use as one Cleric die face"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_SCEPTER_OF_POWER => array(
        'name' => clienttranslate("Scepter of power"),
        'image_file' => 'img/treasures.png',
        'image_index' => 8,
        'weight' => 23,
        'tooltip' => array(
            clienttranslate("Use as one Mage die face"),
        )
    ),
    DR_TYPE_TREASURE_TOKEN . '_' . DR_TOKEN_THIEVES => array(
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
        'ultimate_name' => clienttranslate("Arcane Blade"),
        'imageindex' => 1,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
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
        'ultimate_name' => clienttranslate("Arcane Fury"),
        'imageindex' => 2,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRBattlemage",
        'commandText' => clienttranslate("Battlemage : Discard all dungeon dice"),
    ),

    "4_2" => array(
        'name' => clienttranslate("Mercenary"),
        'specialty' => clienttranslate("When Forming the Party you may re-roll any number of Party dice"),
        'ultimate' => clienttranslate("Defeat up to 2 Monsters"),
        'ultimate_name' => clienttranslate("Calculated Strike"),
        'imageindex' => 3,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRMercenary",
        'commandText' => clienttranslate("Mercenary : Defeat up to 2 Monsters"),
    ),

    "5_2" => array(
        'name' => clienttranslate("Commander"),
        'specialty' => clienttranslate("Fighters defeat 1 extra Monster of any type"),
        'ultimate' => clienttranslate("Re-roll any number of Party and Dungeon dice"),
        'ultimate_name' => clienttranslate("Battlefield Presence"),
        'imageindex' => 4,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRCommander",
        'commandText' => clienttranslate("Commander : Re-roll Party and Dungeon dice"),
    ),

    "4_3" => array(
        'name' => clienttranslate("Knight"),
        'specialty' => clienttranslate("When Forming the Party, all Scrolls become Champions"),
        'ultimate' => clienttranslate("Transform all Monsters to Dragon faces and move them to the Dragon's Lair"),
        'ultimate_name' => clienttranslate("Battlecry"),
        'imageindex' => 5,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRKnight",
        'commandText' => clienttranslate("Knight : Transform all Monsters to Dragon faces"),
    ),

    "5_3" => array(
        'name' => clienttranslate("Dragon Slayer"),
        'specialty' => clienttranslate("When Forming the Party, all Scrolls become Champions. Use 2 different companions to defeat the Dragon (instead of 3)"),
        'ultimate' => clienttranslate("Transform all Monsters to Dragon faces and move them to the Dragon's Lair"),
        'ultimate_name' => clienttranslate("Battlecry"),
        'imageindex' => 6,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRDragonSlayer",
        'commandText' => clienttranslate("Dragon Slayer : Transform all Monsters to Dragon faces"),
    ),

    "4_4" => array(
        'name' => clienttranslate("Half-Goblin"),
        'specialty' => clienttranslate("You may open Chests and quaff Potions at any time during the Monsters Phase"),
        'ultimate' => clienttranslate("Transform 1 Goblin into a Thief. Discard it during the next Regroup Phase"),
        'ultimate_name' => clienttranslate("Plea for Help"),
        'imageindex' => 7,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRHalfGoblin",
        'commandText' => clienttranslate("Half-Goblin : Transform 1 Goblin into a Thief"),
    ),

    "5_4" => array(
        'name' => clienttranslate("Chieftain"),
        'specialty' => clienttranslate("You may open Chests and quaff Potions at any time during the Monsters Phase"),
        'ultimate' => clienttranslate("Transform up to 2 Goblins into Thieves. Discard them during the next Regroup Phase"),
        'ultimate_name' => clienttranslate("Pull Rank"),
        'imageindex' => 8,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRChieftain",
        'commandText' => clienttranslate("Chieftain : Transform up to 2 Goblins into Thieves"),
    ),

    "4_5" => array(
        'name' => clienttranslate("Minstrel"),
        'specialty' => clienttranslate("Thieves may be used as Mages and Mages may be used as Thieves"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'ultimate_name' => clienttranslate("Bard Song"),
        'imageindex' => 9,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRMinstrel",
        'commandText' => clienttranslate("Minstrel : Discard all Dragon dice"),
    ),

    "5_5" => array(
        'name' => clienttranslate("Bard"),
        'specialty' => clienttranslate("Thieves may be used as Mages and Mages may be used as Thieves. Champions defeat 1 extra monster"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'ultimate_name' => clienttranslate("Bard Song"),
        'imageindex' => 10,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRBard",
        'commandText' => clienttranslate("Bard : Discard all Dragon dice"),
    ),

    "4_6" => array(
        'name' => clienttranslate("Enchantress"),
        'specialty' => clienttranslate("Scrolls may be used as any Companion"),
        'ultimate' => clienttranslate("Transform 1 Monster into 1 Potion"),
        'ultimate_name' => clienttranslate("Charm Monster"),
        'imageindex' => 11,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DREnchantress",
        'commandText' => clienttranslate("Enchantress : Transform 1 Monster into 1 Potion"),
    ),

    "5_6" => array(
        'name' => clienttranslate("Beguiler"),
        'specialty' => clienttranslate("Scrolls may be used as any Companion"),
        'ultimate' => clienttranslate("Transform up to 2 Monsters into 1 Potion"),
        'ultimate_name' => clienttranslate("Mesmerize"),
        'imageindex' => 12,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRBeguiler",
        'commandText' => clienttranslate("Beguiler : Transform up to 2 Monsters into 1 Potion"),
    ),

    "4_7" => array(
        'name' => clienttranslate("Occultist"),
        'specialty' => clienttranslate("Clerics may be used as Mages and Mages may be used as Clerics"),
        'ultimate' => clienttranslate("Transform 1 Skeleton into a Fighter. Discard it during the next Regroup Phase"),
        'ultimate_name' => clienttranslate("Animate Dead"),
        'imageindex' => 13,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DROccultist",
        'commandText' => clienttranslate("Occultist : Transform 1 Skeleton into a Fighter"),
    ),

    "5_7" => array(
        'name' => clienttranslate("Necromancer"),
        'specialty' => clienttranslate("Clerics may be used as Mages and Mages may be used as Clerics"),
        'ultimate' => clienttranslate("Transform up to 2 Skeletons into Fighters. Discard it during the next Regroup Phase"),
        'ultimate_name' => clienttranslate("Command Dead"),
        'imageindex' => 14,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRNecromancer",
        'commandText' => clienttranslate("Necromancer : Transform up to 2 Skeletons into Fighters"),
    ),

    "4_8" => array(
        'name' => clienttranslate("Crusader"),
        'specialty' => clienttranslate("Fighters may be used as Clerics and Clerics may be used as Fighters"),
        'ultimate' => clienttranslate("Crusader may be used as a Cleric or a Fighter"),
        'ultimate_name' => clienttranslate("Holy Strike"),
        'imageindex' => 15,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
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
        'ultimate_name' => clienttranslate("Divine Intervention"),
        'imageindex' => 16,
        'expansions' => array(DR_GAME_EXPANSION_BASE, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRPaladin",
        'commandText' => clienttranslate("Paladin : Defeat Monsters, open Chests, quaff Potions and discard Dragon dice"),
    ),

    "4_9" => array(
        'name' => clienttranslate("Viking"),
        'specialty' => clienttranslate("When Forming the Party, remove 2 Party dice from the game and take 5 Champions instead of rolling"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'ultimate_name' => clienttranslate("Viking Fury"),
        'imageindex' => 17,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRViking",
        'commandText' => clienttranslate("Viking : Discard all Dragon dice"),
    ),

    "5_9" => array(
        'name' => clienttranslate("Undead Viking"),
        'specialty' => clienttranslate("When Forming the Party, remove 2 Party dice from the game and take 5 Champions instead of rolling. All Skeletons become Potions"),
        'ultimate' => clienttranslate("Discard all dice from the Dragon's Lair"),
        'ultimate_name' => clienttranslate("Viking Fury"),
        'imageindex' => 18,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRUndeadViking",
        'commandText' => clienttranslate("Undead Viking : Discard all Dragon dice"),
    ),

    "4_10" => array(
        'name' => clienttranslate("Alchemist"),
        'specialty' => clienttranslate("All Chests become Potions"),
        'ultimate' => clienttranslate("Roll 1 Party die from the Graveyard and add it to your Party"),
        'ultimate_name' => clienttranslate("Healing Salve"),
        'imageindex' => 19,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRAlchemist",
        'commandText' => clienttranslate("Alchemist : Roll 1 die from the Graveyard"),
    ),

    "5_10" => array(
        'name' => clienttranslate("Thaumaturge"),
        'specialty' => clienttranslate("All Chests become Potions"),
        'ultimate' => clienttranslate("Roll 2 dice from the Graveyard and add them to your Party"),
        'ultimate_name' => clienttranslate("Transformation Potion"),
        'imageindex' => 20,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRThaumaturge",
        'commandText' => clienttranslate("Thaumaturge : Roll 2 dice from the Graveyard"),
    ),

    "4_11" => array(
        'name' => clienttranslate("Scout"),
        'specialty' => clienttranslate("When Forming the Party, roll 6 Dungeon dice and assign them to levels 1, 2 and 3"),
        'ultimate' => clienttranslate("During the Monster phase, Reduce the Level die by 1 and Retire immediately. Collect Experience equal to the Level die"),
        'ultimate_name' => clienttranslate("Scout Ahead"),
        'imageindex' => 21,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRScout",
        'commandText' => clienttranslate("Scout : Reduce Level by 1 and Retire to the tavern"),
    ),

    "5_11" => array(
        'name' => clienttranslate("Dungeoneer"),
        'specialty' => clienttranslate("When Forming the Party, roll 6 Dungeon dice and assign them to levels 1, 2 and 3"),
        'ultimate' => clienttranslate("Retire immediately. Collect Experience equal to the Level die"),
        'ultimate_name' => clienttranslate("Know when to say when"),
        'imageindex' => 22,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRDungeoneer",
        'commandText' => clienttranslate("Dungeoneer : Retire to the tavern"),
    ),

    "4_12" => array(
        'name' => clienttranslate("Sorceress"),
        'specialty' => clienttranslate("As soon as there are 3 or more dice in the Dragon's Lair, discard all dice in the Dragon's Lair"),
        'ultimate' => clienttranslate("For each die in the Dragon's Lair, discard 1 Monster"),
        'ultimate_name' => clienttranslate("Dragon Breath"),
        'imageindex' => 23,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRSorceress",
        'commandText' => clienttranslate("Sorceress : Discard 1 Monster for each Dragon"),
    ),

    "5_12" => array(
        'name' => clienttranslate("Drake Kin"),
        'specialty' => clienttranslate("As soon as there are 3 or more dice in the Dragon's Lair, discard all dice in the Dragon's Lair"),
        'ultimate' => clienttranslate("For each die in the Dragon's Lair, discard all Monsters of 1 type"),
        'ultimate_name' => clienttranslate("Dragon Fury"),
        'imageindex' => 24,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRDRakeKin",
        'commandText' => clienttranslate("Drake Kin : Discard all Monsters of 1 type for each Dragon"),
    ),

    "4_13" => array(
        'name' => clienttranslate("Archaeologist"),
        'specialty' => clienttranslate("When Forming the Party, draw 2 Treasure Tokens. Discard 6 Treasure Tokens at game end."),
        'ultimate' => clienttranslate("Draw 2 Treasures Tokens from the box and then discard 2 Treasure Tokens"),
        'ultimate_name' => clienttranslate("Treasure Seeker"),
        'imageindex' => 25,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRArchaeologist",
        'commandText' => clienttranslate("Archaeologist : Draw 2 Treasure Tokens, Discard 2 Treasure Tokens"),
    ),

    "5_13" => array(
        'name' => clienttranslate("Tomb Raider"),
        'specialty' => clienttranslate("When Forming the Party, draw 2 Treasure Tokens. Discard 6 Treasure Tokens at game end."),
        'ultimate' => clienttranslate("Draw 2 Treasures Tokens from the box and then discard 1 Treasure Token"),
        'ultimate_name' => clienttranslate("Treasure Seeker"),
        'imageindex' => 26,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRTombRaider",
        'commandText' => clienttranslate("Tomb Raider : Draw 2 Treasure Tokens, Discard 1 Treasure Token"),
    ),

    "4_14" => array(
        'name' => clienttranslate("Leprechaun"),
        'specialty' => clienttranslate("All Potions become Chests. Discard all Treasure Tokens at game end."),
        'ultimate' => clienttranslate("Transform 1 Monster into a Chest"),
        'ultimate_name' => clienttranslate("Fortunate Strike"),
        'imageindex' => 27,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRLeprechaun",
        'commandText' => clienttranslate("Leprechaun : Transform 1 Monster into a Chest"),
    ),

    "5_14" => array(
        'name' => clienttranslate("Clurichaun"),
        'specialty' => clienttranslate("All Potions become Chests. Discard all Treasure Tokens at game end."),
        'ultimate' => clienttranslate("Transform 2 Monsters into 1 Chest"),
        'ultimate_name' => clienttranslate("Bit O'Luck"),
        'imageindex' => 28,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRClurichaun",
        'commandText' => clienttranslate("Clurichaun : Transform 2 Monsters into 1 Chest"),
    ),

    "4_15" => array(
        'name' => clienttranslate("Dwarf"),
        'specialty' => clienttranslate("Start with 2 Party dice in the Graveyard. Whenever a Champion defeats 2+ Monsters, re-roll it instead of discarding."),
        'ultimate' => clienttranslate("Discard 1 Monster for each Champion in your Party, then re-roll all Champions in your Party"),
        'ultimate_name' => clienttranslate("Rally"),
        'imageindex' => 29,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRDwarf",
        'commandText' => clienttranslate("Dwarf : Discard 1 Monster for each Champion in your Party"),
    ),

    "5_15" => array(
        'name' => clienttranslate("Berkerser"),
        'specialty' => clienttranslate("Start with 2 Party dice in the Graveyard. Whenever a Champion defeats 2+ Monsters, re-roll it instead of discarding."),
        'ultimate' => clienttranslate("Roll 4 Party dice from the Graveyard and add them to your party. For the rest of the delve, you may not choose to Retire during Regroup phase unless you defeated a Dragon on that level or reach level 10."),
        'ultimate_name' => clienttranslate("Berserker Rage"),
        'imageindex' => 30,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRBerserker",
        'commandText' => clienttranslate("Berkerser : Roll 4 Party dice from the Graveyard"),
    ),

    "4_16" => array(
        'name' => clienttranslate("Tracker"),
        'specialty' => clienttranslate("Once per level, you may re-roll 1 Goblin"),
        'ultimate' => clienttranslate("Discard 1 Monster of any type"),
        'ultimate_name' => clienttranslate("Called Shot"),
        'imageindex' => 31,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRTracker",
        'commandText' => clienttranslate("Tracker : Discard 1 Monster"),
        'commandSpecialty' => clienttranslate('Specialty : Re-roll 1 Goblin'),
    ),

    "5_16" => array(
        'name' => clienttranslate("Ranger"),
        'specialty' => clienttranslate("Once per level, you may re-roll 1 Goblin"),
        'ultimate' => clienttranslate("Discard 1 Monster of each type"),
        'ultimate_name' => clienttranslate("Flurry of Arrows"),
        'imageindex' => 32,
        'expansions' => array(DR_GAME_EXPANSION_PACK_1, DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRRanger",
        'commandText' => clienttranslate("Ranger : Discard 1 Monster of each type"),
        'commandSpecialty' => clienttranslate('Specialty : Re-roll 1 Goblin'),
    ),

    "4_17" => array(
        'name' => clienttranslate("Guild Leader"),
        'specialty' => clienttranslate("When Forming the Party, roll 8 Party dice instead of 7"),
        'ultimate' => clienttranslate("Set 1 Party die or 1 Dungeon die to any face"),
        'ultimate_name' => clienttranslate("Call in a Favor"),
        'imageindex' => 33,
        'expansions' => array(DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRGuildLeader",
        'commandText' => clienttranslate("Guild Leader : Set 1 Party die or 1 Dungeon die to any face"),
    ),

    "5_17" => array(
        'name' => clienttranslate("Guild Master"),
        'specialty' => clienttranslate("When Forming the Party, roll 8 Party dice instead of 7"),
        'ultimate' => clienttranslate("Set 1 Party die and 1 Dungeon die to any face"),
        'ultimate_name' => clienttranslate("Reinforcements"),
        'imageindex' => 34,
        'expansions' => array(DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRGuildMaster",
        'commandText' => clienttranslate("Guild Master : Set 1 Party die and 1 Dungeon die to any face"),
    ),

    "4_18" => array(
        'name' => clienttranslate("Time Traveler"),
        'specialty' => clienttranslate("Instead of rolling Party dice at the start of each of your delve, set them so that you have one Fighter, Cleric, Mage, Thief, and Scroll, and two Champions"),
        'ultimate' => clienttranslate("Discard one of your Treasures to defeat a Dragon"),
        'ultimate_name' => clienttranslate("Future Technology"),
        'imageindex' => 35,
        'expansions' => array(DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRTimeTraveler",
        'commandText' => clienttranslate("Time Traveler : Defeat a Dragon"),
    ),

    "5_18" => array(
        'name' => clienttranslate("Time Lady"),
        'specialty' => clienttranslate("Instead of rolling Party dice at the start of each of your delve, set them so that you have one Fighter, Cleric, Mage, Thief, and Scroll, and two Champions"),
        'ultimate' => clienttranslate("Discard one of your Treasures or Scroll to defeat a Dragon"),
        'ultimate_name' => clienttranslate("Super-sonic Future Technology"),
        'imageindex' => 36,
        'expansions' => array(DR_GAME_EXPANSION_KICKSTARTER, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRTimeLady",
        'commandText' => clienttranslate("Time Lady : Defeat a Dragon"),
    ),

    "4_19" => array(
        'name' => clienttranslate("Cassandra, The Cursed"),
        'specialty' => clienttranslate("Begin each delve exhausted. While Cassandra is exhausted, all companions defeat 1 additionnal Monster. When quaffing potions, refresh Cassandra, The Cursed"),
        'ultimate' => clienttranslate("Discard 1 chest or potion (there must be a chest or potion to discard)"),
        'ultimate_name' => clienttranslate("Bestial Fury"),
        'imageindex' => 37,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRCassandra",
        'commandText' => clienttranslate("Cassandra : Discard 1 chest or potion"),
    ),

    "4_20" => array(
        'name' => clienttranslate("Marpesia, Amazon Queen"),
        'specialty' => clienttranslate("Requires 4 Dragon faces (instead of 3) before the Dragon arrives. Champions kill only 1 Monster and open only 1 chest"),
        'ultimate' => clienttranslate("Roll all dice from the Graveyard, discard any Scrolls, and add the rest to your party"),
        'ultimate_name' => clienttranslate("Rallying Cry"),
        'imageindex' => 38,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRMarpesia",
        'commandText' => clienttranslate("Marpesia : Roll all dice from the Graveyard"),
    ),

    "4_21" => array(
        'name' => clienttranslate("Tou'lak, Minotaur Chieftain"),
        'specialty' => clienttranslate("You may re-roll 1 Dungeon die (not a Dragon face) each level"),
        'ultimate' => clienttranslate("Discard all Monsters on the current level"),
        'ultimate_name' => clienttranslate("Deafening Roar"),
        'imageindex' => 39,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRToulak",
        'commandText' => clienttranslate("Tou'lak : Discard all Monsters on the current level"),
        'commandSpecialty' => clienttranslate('Specialty : Re-roll 1 Dungeon die'),
    ),

    "4_22" => array(
        'name' => clienttranslate("Amar-Suen, King of the Ancients"),
        'specialty' => clienttranslate("Thieves defeat 1 additional Monster"),
        'ultimate' => clienttranslate("Discard all Monsters of 1 type. Draw 1 Treasure for each Monster discarded this way"),
        'ultimate_name' => clienttranslate("Touch of Midas"),
        'imageindex' => 40,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRAmarSuen",
        'commandText' => clienttranslate("Amar-Suen : Discard all Monsters of 1 type and draw Treasures"),
    ),

    "4_23" => array(
        'name' => clienttranslate("Tristan, Lone Mercenary"),
        'specialty' => clienttranslate("When defeating 2 or more Monsters with 1 Companion, draw 1 treasure from the box. Discard 1 Treasure to refresh Tristan, Lone Mercenary"),
        'ultimate' => clienttranslate("Re-roll any number of Dungeon dice (including dice in the Dragon's Lair)."),
        'ultimate_name' => clienttranslate("Calculated Risk"),
        'imageindex' => 41,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRTristan",
        'commandText' => clienttranslate("Tristan : Select any number of Dungeon dice to re-roll"),
        'commandSpecialty' => clienttranslate('Specialty : Discard 1 Treasure token to Refresh'),
    ),

    "4_24" => array(
        'name' => clienttranslate("Loeg Yllavyre, Dragon Lord"),
        'specialty' => clienttranslate("When Forming the Party, set 2 dice to Scrolls. Discard a Scroll to transform a Monster into a Dragon face. When facing all 3 Monster types, refresh Loeg Yllavyre"),
        'ultimate' => clienttranslate("Take control of all dice in the Dragon's Lair. They count as companions (defeat 1 Monster, open 1 chest, or quaff all potions). Discard them duging the next Regroup Phase"),
        'ultimate_name' => clienttranslate("Word of Command"),
        'imageindex' => 42,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRLoegYllavyre",
        'commandText' => clienttranslate("Loeg Yllavyre : Take control of all dice in the Dragon's Lair"),
        'commandSpecialty' => clienttranslate('Specialty : Discard a Scroll to transform a Monster into a Dragon'),
    ),

    "4_25" => array(
        'name' => clienttranslate("Alexandra, Keeper of Knowledge"),
        'specialty' => clienttranslate("You may use Scrolls as Champions. Discard any Party die to re-roll any number of Party and Dungeon dice"),
        'ultimate' => clienttranslate("Discard a Scroll (instead of 3 different companions) to defeat a Dragon during the Dragon Phase"),
        'ultimate_name' => clienttranslate("Forbidden Knowledge"),
        'imageindex' => 43,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRAlexandra",
        'commandText' => clienttranslate("Alexandra : Discard a Scroll to defeat a Dragon"),
        'commandSpecialty' => clienttranslate('Specialty : Discard a Party Die to re-roll Party and Dungeon dice'),
    ),

    "4_26" => array(
        'name' => clienttranslate("Szopin, Infamous Warlord"),
        'specialty' => clienttranslate("When defeating 2 or more Monsters with 1 Companion, refresh Szopin"),
        'ultimate' => clienttranslate("Discard up to 2 Dungeon dice (including dice in the Dragon's Lair)"),
        'ultimate_name' => clienttranslate("Strike Fear"),
        'imageindex' => 44,
        'expansions' => array(DR_GAME_EXPANSION_PACK_2, DR_GAME_EXPANSION_ALL),
        'heroclass' => "DRSzopin",
        'commandText' => clienttranslate("Szopin : Select up to 2 Dungeon dice to discard"),
    ),

);

$this->command_infos = array(


    1 => array(
        'name' => 'fightMonster',
        'text' => clienttranslate('Fight Monsters'),
        'php_class' => 'DRCommandFightMonster',
        'always_visible' => true,
        'html_zone' => 'zone_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => 'checkFightGoblin',
                'confirmation' => clienttranslate("Are you sure you do not want to fight all Goblins?")
            ),
            array(
                'askConfirmation' => 'checkFightOoze',
                'confirmation' => clienttranslate("Are you sure you do not want to fight all Oozes?")
            ),
            array(
                'askConfirmation' => 'checkFightSkeleton',
                'confirmation' => clienttranslate("Are you sure you do not want to fight all Skeletons?")
            ),
        )
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
                'confirmation' => clienttranslate("Are you sure you do not want to open all Chests?")
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
                // 'askConfirmation' => 'checkNonSelectedPotion',
                'askConfirmation' => 'false',
                'confirmation' => clienttranslate("Are you sure you do not want to quaff all Potions?")
            )
        )
    ),

    7 => array(
        'name' => 'endQuaffPhase',
        'text' => clienttranslate('Next phase'),
        'php_class' => 'DRCommandEndQuaffPhase',
        'always_visible' => false,
        'html_zone' => 'zone_phases_actions'
    ),

    8 => array(
        'name' => 'openChestScout',
        'text' => clienttranslate('Open Chest'),
        'php_class' => 'DRCommandOpenChestScout',
        'always_visible' => true,
        'html_zone' => 'zone_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => 'always',
                'confirmation' => clienttranslate("Are you sure you want to open Chests? If you do, you will not be able to use your Scout Ultimate ability before fighting Dragons.")
            )
        )
    ),

    9 => array(
        'name' => 'quaffPotionScout',
        'text' => clienttranslate('Quaff Potion'),
        'php_class' => 'DRCommandQuaffPotionScout',
        'always_visible' => true,
        'html_zone' => 'zone_actions',
        'confirmations' => array(
            array(
                'askConfirmation' => 'always',
                'confirmation' => clienttranslate("Are you sure you want to quaff Potions? If you do, you will not be able to use your Scout Ultimate ability before fighting Dragons.")
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
    
    46 => array(
        'name' => 'endFormingPartyPhaseLoegYllavyre',
        'text' => clienttranslate('Confirm the selection'),
        'php_class' => 'DRCommandSelectScrollPartyPhase',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    47 => array(
        'name' => 'selectionDice',
        'text' => clienttranslate('Confirm the selection'),
        'php_class' => 'DRCommandDiceSelection',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    48 => array(
        'name' => 'discardTreasure',
        'text' => clienttranslate('Confirm the selection'),
        'php_class' => 'DRCommandDiscardTreasure',
        'always_visible' => true,
        'html_zone' => 'zone_actions'
    ),

    49 => array(
        'name' => 'endFormingPartyPhaseScout',
        'text' => clienttranslate('Confirm the selection'),
        'php_class' => 'DRCommandEndFormingPartyPhaseScout',
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
        'always_visible' => true,
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

    69 => array(
        'name' => 'heroSpecialty',
        'text' => 'Hero Specialty',
        'php_class' => 'DRCommandHeroSpecialty',
        'html_zone' => 'zone_actions',
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

    80 => array(
        'name' => 'getScores',
        'text' => clienttranslate('Get Scores'),
        'button_color' => "blue",
        'php_class' => 'DRCommandScoring',
        'html_zone' => 'zone_actions'
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
