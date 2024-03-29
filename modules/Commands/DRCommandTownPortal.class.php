<?php

class DRCommandTownPortal extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        $items = $this->game->components->getActivePlayerUsableItems();
        $townPortal = DRUtils::filter($items, 'DRTreasureToken::isTownPortal');
        if(sizeof($townPortal) == 0) {
            return array();
        }
        return array('monsterPhase', 'dragonPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;
        
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        return DRItem::containsSameAs($items, DRTreasureToken::getToken(DR_TOKEN_TOWN_PORTAL));
    }

    public function execute($sub_command_id)
    {
        // Show the message before the update score point
        $this->game->notif->useTownPortal();

        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $portals = DRItem::getSameAs($items, DRTreasureToken::getToken(DR_TOKEN_TOWN_PORTAL));

        // Discard the token
        $itemsUpdate = DRItem::setZone(array($portals[0]), DR_ZONE_BOX);
        $this->game->manager->updateItems($itemsUpdate);
        $this->game->NTA_itemMove($itemsUpdate);

        // Scoring
        $points = $this->game->vars->getDungeonLevel();
        $this->game->incPlayerScore($points);
        $this->game->notif->updateScorePlayer($points);
        $this->game->notif->updatedScores();

        // Stats
        $this->game->stats->incLevelCompleted($points);

        // Go to the next state
        $this->game->gamestate->nextState('townPortal');
    }
}
