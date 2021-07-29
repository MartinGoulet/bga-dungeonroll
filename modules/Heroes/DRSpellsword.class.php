<?php

class DRSpellsword extends DRStandardHero
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
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $fighters = DRUtils::filter($itemsInPlay, 'DRItem::isFighter');
        $mages = DRUtils::filter($itemsInPlay, 'DRItem::isMage');

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $goblins = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isGoblin');
        $oozes = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isOoze');

        // Fighters may be used as Mages and Mages may be used as Fighters.
        if (sizeof($fighters) == 1 && sizeof($oozes) == sizeof($monsters))
            return sizeof($fighters) + sizeof($oozes) == sizeof($itemsInPlay);

        if (sizeof($mages) == 1 && sizeof($goblins) == sizeof($monsters))
            return sizeof($mages) + sizeof($goblins) == sizeof($itemsInPlay);

        return false;
    }

    /**
     * Game breaking rules
     */
    function canDefeatDragon()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        $companions = DRItem::getCompanions($itemsInPlay);
        $fighters = DRUtils::filter($companions, 'DRItem::isFighter');
        $mages = DRUtils::filter($companions, 'DRItem::isMage');

        if (sizeof($companions) == 3) {
            if (sizeof($fighters) == 2 && sizeof($mages) == 0)
                return true;
            elseif (sizeof($fighters) == 0 && sizeof($mages) == 2)
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
            $die = DRPartyDice::getDie(DIE_FIGHTER);
        } else if ($sub_command_id == 2) {
            $die = DRPartyDice::getDie(DIE_MAGE);
        }

        $die['owner'] = $this->game->getActivePlayerId();
        $die['zone'] = ZONE_PLAY;
        $this->game->manager->insertTemporaryAbility($die);

        $this->game->notif->ultimateSpellSword($die);
    }
}
