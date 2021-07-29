<?php

class DRCrusader extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    /**
     * Game breaking rules
     */
    function canDefeatMonster()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $fighters = DRUtils::filter($itemsInPlay, 'DRItem::isFighter');
        $clerics = DRUtils::filter($itemsInPlay, 'DRItem::isCleric');

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $goblins = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isGoblin');
        $skeletons = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isSkeleton');

        // Fighters may be used as Clerics and Clerics may be used as Fighters.
        if (sizeof($fighters) == 1 && sizeof($skeletons) == sizeof($monsters))
            return sizeof($fighters) + sizeof($skeletons) == sizeof($itemsInPlay);

        if (sizeof($clerics) == 1 && sizeof($goblins) == sizeof($monsters))
            return sizeof($clerics) + sizeof($goblins) == sizeof($itemsInPlay);

        return false;
    }

    /**
     * Game breaking rules
     */
    function canDefeatDragon()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);

        $companions = DRItem::getCompanions($itemsInPlay);
        $fighters = DRUtils::filter($companions, 'DRItem::isFighter');
        $clerics = DRUtils::filter($companions, 'DRItem::isCleric');

        if (sizeof($companions) == 3) {
            if (sizeof($fighters) == 2 && sizeof($clerics) == 0)
                return true;
            elseif (sizeof($fighters) == 0 && sizeof($clerics) == 2)
                return true;
        }

        return false;

    }

    function canLevelUp()
    {
        return true;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        return in_array($this->getState(), array('monsterPhase', 'lootPhase', 'dragonPhase'));
    }

    function executeUltimate($sub_command_id)
    {
        DRUtils::userAssertTrue(
            "Command invalide : " . $sub_command_id,
            in_array($sub_command_id, array(1, 2))
        );

        if ($sub_command_id == 1) {
            $die = DRPartyDice::getDie(DR_DIE_FIGHTER);
        } else if ($sub_command_id == 2) {
            $die = DRPartyDice::getDie(DR_DIE_CLERIC);
        }

        $die['owner'] = $this->game->getActivePlayerId();
        $die['zone'] = DR_ZONE_PLAY;
        $this->game->manager->insertTemporaryAbility($die);

        $this->game->notif->ultimateCrusader($die);
    }
}
