<?php

class DRTreasureToken
{
    static function getToken($value) {
        return array(
            'type' => TYPE_TREASURE_TOKEN,
            'value' => $value
        );
    }

    static function isAnyTokenFound($items) {
        $tokens = array_values(array_filter($items, function($item) {
            DRItem::isTreasureToken($item);
        }));
        return sizeof($tokens) > 0;
    }

    static function getTreasureTokens($items) {
        return array_values(array_filter($items, function($item) {
            return DRITem::isTreasureToken($item);
        }));
    }

    static function isVorpalSword($item)
    {
        return $item['value'] == TOKEN_VORPAL_SWORD && DRItem::isTreasureToken($item);
    }

    static function isTalisman($item)
    {
        return $item['value'] == TOKEN_TALISMAN && DRItem::isTreasureToken($item);
    }

    static function isScepterOfPower($item)
    {
        return $item['value'] == TOKEN_SCEPTER_OF_POWER && DRItem::isTreasureToken($item);
    }

    static function isThieves($item)
    {
        return $item['value'] == TOKEN_THIEVES && DRItem::isTreasureToken($item);
    }

    static function isScroll($item)
    {
        return $item['value'] == TOKEN_SCROLL && DRItem::isTreasureToken($item);
    }

    static function isRingOfInvisibility($item)
    {
        return $item['value'] == TOKEN_RING_INVISIBILITY && DRItem::isTreasureToken($item);
    }

    static function isDragonBait($item)
    {
        return $item['value'] == TOKEN_DRAGON_BAIT && DRItem::isTreasureToken($item);
    }

    static function isDragonScales($item)
    {
        return $item['value'] == TOKEN_DRAGON_SCALES && DRItem::isTreasureToken($item);
    }

    static function isPotion($item)
    {
        return $item['value'] == TOKEN_POTION && DRItem::isTreasureToken($item);
    }

    static function isTownPortal($item)
    {
        return $item['value'] == TOKEN_TOWN_PORTAL && DRItem::isTreasureToken($item);
    }
}
