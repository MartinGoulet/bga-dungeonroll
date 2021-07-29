<?php

class DRGuildMaster extends DRGuildLeader
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

    function getSelectionDiceText() 
    {
        return clienttranslate("1 Party die and 1 Dungeon die");
    }

    function isSelectionDiceCorrect()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $party = DRPartyDice::getPartyDice($items);
        $dungeon = DRDungeonDice::getDungeonDice($items);
        return (sizeof($party) == 1 || sizeof($dungeon) == 1) && sizeof($items) <= 2;
    }

}
