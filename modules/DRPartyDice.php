<?php

require_once ("constants.inc.php");

class DRPartyDice
{

    static function getDice($value, $id = null)
    {
        return array(
            'value' => $value,
            'type' => TYPE_PARTY_DIE,
            'id'   => $id
        );
    }

    static function getDie($value) {
        return array(
            'type' => TYPE_PARTY_DIE,
            'value' => $value
        );
    }

    static function getPartyDice($dice)
    {
        // Find party dice
        return array_values(array_filter($dice, function ($dice) {
            return DRItem::isPartyDie($dice);
        }));
    }

    static function getCompanionDice($dice)
    {
        // Find all dice except scroll
        return array_values(array_filter($dice, function ($dice) {
            return DRItem::isPartyDie($dice) && !DRPartyDice::isScroll($dice);
        }));
    }

    static function getScrollDice($dice)
    {
        // Find all dice except scroll
        return array_values(array_filter($dice, function ($die) {
            return DRItem::isPartyDie($die) && DRPartyDice::isScroll($die);
        }));
    }

    static function getDiceByFace($dice, $value) {
        return array_values(array_filter($dice, function ($dice) use ($value) {
            return DRItem::isPartyDie($dice) && $dice['value'] == $value;
        }));
    }

    static function isScroll($dice)
    {
        return $dice['value'] == DIE_SCROLL && DRItem::isPartyDie($dice);
    }

    static function isMage($dice)
    {
        return $dice['value'] == DIE_MAGE && DRItem::isPartyDie($dice);
    }

    static function isCleric($dice)
    {
        return $dice['value'] == DIE_CLERIC && DRItem::isPartyDie($dice);
    }

    static function isFighter($dice)
    {
        return $dice['value'] == DIE_FIGHTER && DRItem::isPartyDie($dice);
    }

    static function isThief($dice)
    {
        return $dice['value'] == DIE_THIEF && DRItem::isPartyDie($dice);
    }

    static function isChampion($dice)
    {
        return $dice['value'] == DIE_CHAMPION && DRItem::isPartyDie($dice);
    }

    static function isGenericDragon($die)
    {
        return $die['value'] == DIE_GENERIC_DRAGON && DRItem::isPartyDie($die);
    }
}
