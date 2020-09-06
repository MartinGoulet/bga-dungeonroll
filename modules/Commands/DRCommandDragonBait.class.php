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

        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        return DRItem::containsSameAs($items, DRTreasureToken::getToken(TOKEN_DRAGON_BAIT));
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $dragonBaits = DRItem::getSameAs($itemsInPlay, DRTreasureToken::getToken(TOKEN_DRAGON_BAIT));

        $items    = $this->game->components->getActivePlayerItems();
        $monstersBefore = DRDungeonDice::getMonsterDices($items);
        $monsters = DRDungeonDice::getMonsterDices($items);
        $dragons = $this->game->transformMonstersToDragons($monsters);

        // Discard the token
        $itemsUpdate = array_merge(
            $dragonBaits = DRItem::setZone($dragonBaits, ZONE_BOX),
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
