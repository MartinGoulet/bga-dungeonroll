<?php

class DRBard extends DRMinstrel
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    /**
     * Game breaking rules
     */
    function canLevelUp()
    {
        return false;
    }

    function canDefeatMonster()
    {
        if(parent::canDefeatMonster()) {
            return true;
        }

        // Champions defeat 1 extra monster.
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $champions   = DRUtils::filter($itemsInPlay, 'DRPartyDice::isChampion');
        $goblins     = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isGoblin');
        $oozes       = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isOoze');
        $skeleton    = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isSkeleton');

        $values = array(sizeof($goblins), sizeof($oozes), sizeof($skeleton));
        sort($values);

        return sizeof($champions) == 1 &&
               $values[2] >= 1 && $values[1] == 1 && $values[0] == 0 &&
               (sizeof($champions) + sizeof(DRDungeonDice::getMonsterDices($itemsInPlay))) == sizeof($itemsInPlay);
    }

}
