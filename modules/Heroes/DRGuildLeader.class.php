<?php

class DRGuildLeader extends DRStandardHero
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

    function getTotalPartyDice()
    {
        return 8;
    }

    function selectDice($partyValue, $dungeonValue)
    {
        // Callback from ultimate

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $party = DRPartyDice::getPartyDice($itemsInPlay);
        $dungeon = DRDungeonDice::getDungeonDice($itemsInPlay);

        if ($partyValue >= 1 && $partyValue <= 6 && sizeof($party) == 1) {
            $before = $party;
            $party[0]['value'] = $partyValue;
            $this->game->manager->updateItems($party);
            $this->game->notif->ultimateGuildLeader($before, $party);
        }

        if ($dungeonValue >= 1 && $dungeonValue <= 6 && sizeof($dungeon) == 1) {
            $before = $dungeon;
            $dungeon[0]['value'] = $dungeonValue;
            if($dungeonValue == DIE_DRAGON) {
                $dungeon = DRItem::setZone($dungeon, ZONE_DRAGON_LAIR);
            }
            $this->game->manager->updateItems($dungeon);
            $this->game->notif->ultimateGuildLeader($before, $dungeon);
        }


        $this->game->gamestate->nextState();
    }

    function isSelectionDiceCorrect()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $party = DRPartyDice::getPartyDice($items);
        $dungeon = DRDungeonDice::getDungeonDice($items);
        return (sizeof($party) == 1 || sizeof($dungeon) == 1) && sizeof($items) == 1;
    }

    function getSelectionDiceText() 
    {
        return clienttranslate("1 Party die or 1 Dungeon die");
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        return true;
    }

    function executeUltimate($sub_command_id)
    {
        $this->game->gamestate->nextState('guildLeader');
    }
}
