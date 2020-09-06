<?php

class DRCommandEndFormingPartyPhaseScout extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates()
    {
        return array('postFormingPartyScout');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        return sizeof($items) == $this->getNumberOfDiceToConfirm();
    }

    public function execute($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        // Notify the return
        $items = DRItem::setZone($items, ZONE_BOX);
        $this->game->NTA_itemMove($items);

        $items = DRItem::setZone($items, "LEVEL_" . sizeof($items));
        $this->game->manager->updateItems($items);
        $this->game->notif->scoutSelectDungeonDice($items, sizeof($items));

        // All remaining dice will go for the level 3
        if (sizeof($items) == 2) {
            $dungeon = $this->game->components->getActivePlayerItemsByZone(ZONE_DUNGEON);

            $dungeon = DRItem::setZone($dungeon, ZONE_BOX);
            $this->game->NTA_itemMove($dungeon);

            $dungeon = DRItem::setZone($dungeon, "LEVEL_" . sizeof($dungeon));
            $this->game->manager->updateItems($dungeon);
            $this->game->notif->scoutSelectDungeonDice($dungeon, sizeof($dungeon));

        }

        // Go to the next state
        if (sizeof($items) == 1) {
            $this->game->gamestate->nextState('next');
        } else {
            $this->game->gamestate->nextState('end');
        }
    }

    function getNumberOfDiceToConfirm()
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $items = DRDungeonDice::getDungeonDice($items);

        $nbr = 2;
        if (sizeof($items) == 6) {
            $nbr = 1;
        }
        return $nbr;
    }
}
