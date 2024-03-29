<?php

class DRCommandDragonBait extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates()
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $baits = DRUtils::filter($items, 'DRTreasureToken::isDragonBait');
        if(sizeof($baits) == 0) {
            return array();
        }
        return array('monsterPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        return DRItem::containsSameAs($items, DRTreasureToken::getToken(DR_TOKEN_DRAGON_BAIT));
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $dragonBaits = array_slice(DRItem::getSameAs($itemsInPlay, DRTreasureToken::getToken(DR_TOKEN_DRAGON_BAIT)), 0, 1);

        $items    = $this->game->components->getActivePlayerUsableItems();
        $monstersBefore = DRDungeonDice::getMonsterDices($items);
        $monsters = DRDungeonDice::getMonsterDices($items);
        $dragons = $this->game->transformMonstersToDragons($monsters);

        // Discard the token
        $itemsUpdate = array_merge(
            $dragonBaits = DRItem::setZone($dragonBaits, DR_ZONE_BOX),
            $dragons
        );

        $this->game->manager->updateItems($itemsUpdate);
        $this->game->NTA_itemMove($itemsUpdate);

        $this->game->notif->useDragonBait($dragonBaits, $monstersBefore, $dragons);

        $hero = $this->game->components->getActivePlayerHero();
        $hero->afterDragonBait();

        // Go to the next state
        $this->game->gamestate->nextState("dragonBait");
    }
}
