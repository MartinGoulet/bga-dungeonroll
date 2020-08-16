<?php

class DRKnight extends DRStandardHero
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
        return true;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        // True if at least 1 monster
        $items = $this->game->components->getActivePlayerItems();
        $monsters = DRDungeonDice::getMonsterDices($items);
        return sizeof($monsters) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerItems();
        $monsters = DRDungeonDice::getMonsterDices($items);
        $dragons = $this->game->transformMonstersToDragons($monsters);

        $this->game->manager->updateItems($dragons);
        $this->game->notif->ultimateKnightDragonSlayer($dragons);
    }

    /**
     * States
     */

    function stateAfterFormingParty(&$dice)
    {
        // When Forming the Party, all Scrolls become Champions
        $changes = array();
        // &$die : Alter the die in parameter
        foreach ($dice as &$die) {
            if (DRPartyDice::isScroll($die)) {
                $die['value'] = DIE_CHAMPION;
                $changes[] = $die;
            }
        }

        // Notify the modification
        if (sizeof($changes) > 0) {
            $this->game->notif->changeScrollToChampion($changes);
        }
    }
}
