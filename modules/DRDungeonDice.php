<?php

require_once ('DRUtils.php');
require_once ('constants.inc.php');

class DRDungeonDice
{

    static function getDie($value)
    {
        return array(
            'value' => $value,
            'type' => DR_TYPE_DUNGEON_DIE
        );
    }

    static function isMonsterDice($dice)
    {
        return DRItem::isDungeonDie($dice) && in_array($dice['value'], array(
            DR_DIE_GOBLIN, DR_DIE_OOZE, DR_DIE_SKELETON
        ));
    }

    static function getMonsterTypeCount($dicesInPlay)
    {
        // Find monster dice
        $monsterDices = array_filter($dicesInPlay, function ($dice) {
            return self::isMonsterDice($dice);
        });
        // Find all different types of monsters
        $unique = array_unique(array_column($monsterDices, 'value'));
        // Return the number of type of monsters
        return sizeof($unique);
    }

    static function getMonsterDices($items)
    {
        // Find all dice except scroll
        return DRUtils::filter($items, function($item) {
            return self::isMonsterDice($item);
        });
    }

    static function getDungeonDice($items)
    {
        // Find all dice except scroll
        return array_values(array_filter($items, function ($item) {
            return DRItem::isDungeonDie($item);
        }));
    }

    static function getDungeonDiceWithoutDragon($items)
    {
        // Find all dice except dragon
        return array_values(array_filter($items, function ($item) {
            return DRItem::isDungeonDie($item) && !self::isDragon($item);
        }));
    }

    static function getDragonDice($dice)
    {
        return self::getDiceByFace($dice, DR_DIE_DRAGON);
    }

    static function getDiceByFace($dice, $value)
    {
        return array_values(array_filter($dice, function ($dice) use ($value) {
            return DRItem::isDungeonDie($dice) && $dice['value'] == $value;
        }));
    }

    static function isDragon($dice)
    {
        return $dice['value'] == DR_DIE_DRAGON && DRItem::isDungeonDie($dice);
    }

    static function isPotion($dice)
    {
        return $dice['value'] == DR_DIE_POTION && DRItem::isDungeonDie($dice);
    }

    static function isChest($dice)
    {
        return $dice['value'] == DR_DIE_CHEST && DRItem::isDungeonDie($dice);
    }

    static function isGoblin($dice)
    {
        return $dice['value'] == DR_DIE_GOBLIN && DRItem::isDungeonDie($dice);
    }

    static function isOoze($dice)
    {
        return $dice['value'] == DR_DIE_OOZE && DRItem::isDungeonDie($dice);
    }

    static function isSkeleton($dice)
    {
        return $dice['value'] == DR_DIE_SKELETON && DRItem::isDungeonDie($dice);
    }
}
