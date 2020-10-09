<?php

class DRAmarSuen extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    /**
     * Game breaking rules
     */
    function canDefeatMonster()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $thieves = DRUtils::filter($itemsInPlay, 'DRItem::isThief');
        if (sizeof($thieves) != 1) {
            return false;
        }

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        return sizeof($monsters) <= 2;
    }

    /**
     * Must Overrides
     */

    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monstersByGroup = $this->groupByDiceValue($monsters);
        return sizeof($monstersByGroup) == 1;
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monsters = DRItem::setZone($monsters, ZONE_BOX);
        
        $player_id = $this->game->getActivePlayerId();

        $treasures = $this->game->components->getItemsByTypeAndZone(TYPE_TREASURE_TOKEN, ZONE_BOX);
        $treasures = DRUtils::random_item($treasures, sizeof($monsters));
        $treasures = DRItem::setOwner($treasures, $player_id);
        $treasures = DRItem::setZone($treasures, ZONE_INVENTORY);

        for ($i = 0; $i < sizeof($treasures); $i++) {
            $treasures[$i]['from'] = $monsters[$i]['id'];
            $treasures[$i]['previous_zone'] = ZONE_PLAY;
        }

        $this->game->stats->incTreasureOpen(sizeof($treasures));
        $this->game->manager->updateItems($monsters);
        $this->game->manager->updateItems($treasures);
        $this->game->notif->ultimateAmarSuen($monsters, $treasures);

        $this->game->gamestate->nextState('ultimate');
    }

}
