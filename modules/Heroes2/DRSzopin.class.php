<?php

class DRSzopin extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    function afterDefeatMonster($party, $monsters)
    {
        // When defeating 2 or more monsters with 1 companions, refresh Szopin
        if (sizeof($party) == 1 && sizeof($monsters) >= 2) {
            $this->game->vars->setIsHeroActivated(false);
            $this->game->notif->refreshSzopin($monsters);
        }
    }

    function discardDice()
    {
        // Callback from ultimate

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $itemsInPlay = DRItem::setZone($itemsInPlay, DR_ZONE_BOX);
        $this->game->manager->updateItems($itemsInPlay);
        $this->game->notif->ultimateSzopin($itemsInPlay);

        $this->game->gamestate->nextState();
    }

    function isSelectionDiceCorrect()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $dungeon = DRDungeonDice::getDungeonDice($items);
        return sizeof($dungeon) == sizeof($items) &&
               sizeof($dungeon) >= 1 && 
               sizeof($dungeon) <= 2;
    }

    function getSelectionDiceText() 
    {
        return clienttranslate("up to 2 dungeon dice (including Dragon dice)");
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
        $this->game->gamestate->nextState('szopin');
    }
}
