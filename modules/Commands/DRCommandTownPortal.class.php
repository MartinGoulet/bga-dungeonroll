<?php

class DRCommandTownPortal extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;
        
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        return DRItem::containsSameAs($items, DRTreasureToken::getToken(TOKEN_TOWN_PORTAL));
    }

    public function execute($sub_command_id)
    {
        $items   = $this->game->components->getActivePlayerUsableItems();
        $portals = DRItem::getSameAs($items, DRTreasureToken::getToken(TOKEN_TOWN_PORTAL));

        // Discard the token
        $itemsUpdate = DRItem::setZone(array($portals[0]), ZONE_BOX);
        $this->game->manager->updateItems($itemsUpdate);
        $this->game->NTA_itemMove($itemsUpdate);

        // Scoring
        $points = $this->game->vars->getDungeonLevel();
        $this->game->incPlayerScore($points);
        $this->game->notif->updatedScores();

        // Stats
        $this->game->stats->incLevelCompleted($points);

        // Go to the next state
        $this->game->gamestate->nextState('townPortal');
    }
}
