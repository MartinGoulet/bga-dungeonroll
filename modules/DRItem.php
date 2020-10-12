<?php

require_once("constants.inc.php");

class DRItem
{

    static function isPartyDie($dice)
    {
        return $dice['type'] == TYPE_PARTY_DIE;
    }

    static function isDungeonDie($dice)
    {
        return $dice['type'] == TYPE_DUNGEON_DIE;
    }

    static function isTreasureToken($item)
    {
        return $item['type'] == TYPE_TREASURE_TOKEN;
    }

    static function setZone($items, $zone)
    {
        $output = array();
        foreach ($items as $item) {
            $item['previous_zone'] = $item['zone'];
            $item['zone'] = $zone;
            $output[] = $item;
        }
        return $output;
    }


    static function setOwner($items, $owner)
    {
        $output = array();
        foreach ($items as $item) {
            $item['previous_owner'] = $item['owner'];
            $item['owner'] = $owner;
            $output[] = $item;
        }
        return $output;
    }

    static function excludeItem($items, $itemToExclude)
    {
        // Find all items except the item to exclude
        return array_values(array_filter($items, function ($dice) use ($itemToExclude) {
            return $dice['id'] !== $itemToExclude['id'];
        }));
    }

    static function getSameAs($items, $itemTemplate)
    {
        return array_values(array_filter($items, function ($item) use ($itemTemplate) {
            return $item['type'] == $itemTemplate['type'] &&
                $item['value'] == $itemTemplate['value'];
        }));
    }

    static function containsSameAs($items, $itemTemplate)
    {
        return sizeof(self::getSameAs($items, $itemTemplate)) > 0;
    }

    static function isCompanionDice($item)
    {
        return  DRItem::isPartyDie($item) && !DRPartyDice::isScroll($item);
    }

    static function isFighter($item)
    {
        return DRPartyDice::isFighter($item) || DRTreasureToken::isVorpalSword($item);
    }

    static function isMage($item)
    {
        return DRPartyDice::isMage($item) || DRTreasureToken::isScepterOfPower($item);
    }

    static function isCleric($item)
    {
        return DRPartyDice::isCleric($item) || DRTreasureToken::isTalisman($item);
    }

    static function isThief($item)
    {
        return DRPartyDice::isThief($item) || DRTreasureToken::isThieves($item);
    }

    static function isScroll($item)
    {
        return DRPartyDice::isScroll($item) || DRTreasureToken::isScroll($item);
    }

    static function isTemporaryItem($item)
    {
        return $item['id'] >= 1000 && $item['id'] < 2000;
    }

    static function isTemporaryAbility($item)
    {
        return $item['id'] >= 2000 && $item['id'] < 3000;
    }

    static function isCompanionToken($item)
    {
        return  DRItem::isTreasureToken($item) && ($item['value'] == TOKEN_SCEPTER_OF_POWER ||
            $item['value'] == TOKEN_TALISMAN ||
            $item['value'] == TOKEN_THIEVES ||
            $item['value'] == TOKEN_VORPAL_SWORD);
    }

    static function getCompanions($items)
    {
        return array_values(array_filter($items, function ($item) {
            return self::isFighter($item) ||
                self::isMage($item) ||
                self::isCleric($item) ||
                self::isThief($item) ||
                DRPartyDice::isChampion($item) ||
                DRPartyDice::isGenericDragon($item);
        }));
    }

    static function getCompanionTypes($items)
    {
        $types = array();
        foreach ($items as $item) {
            $types[] = self::getCompanionType($item);
        }
        return $types;
    }

    static function getCompanionType($item)
    {
        if (DRItem::isPartyDie($item)) {

            return $item['value'];
            
        } else if (DRItem::isTreasureToken($item)) {

            switch ($item['value']) {
                case TOKEN_VORPAL_SWORD:
                    return DIE_FIGHTER;

                case TOKEN_SCEPTER_OF_POWER:
                    return DIE_MAGE;

                case TOKEN_TALISMAN:
                    return DIE_CLERIC;

                case TOKEN_THIEVES:
                    return DIE_THIEF;
            }
        }
    }

    static function rollDice($dice)
    {
        return array_values(array_map(function ($die) {
            $die['value'] = bga_rand(1, 6);
            return $die;
        }, $dice));
    }
}
