<?php

class DRBattlemage extends DRSpellsword
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

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        return in_array($this->getState(), array('monsterPhase', 'lootPhase', 'dragonPhase'));
    }

    function executeUltimate($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $dungeons = DRDungeonDice::getDungeonDice($items);

        $dungeons = DRItem::setZone($dungeons, ZONE_BOX);
        $this->game->manager->updateItems($dungeons);
        $this->game->notif->ultimateBattlemage($dungeons);
    }

}