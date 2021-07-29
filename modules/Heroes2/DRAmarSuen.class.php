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
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $thieves = DRUtils::filter($itemsInPlay, 'DRItem::isThief');
        if (sizeof($thieves) != 1) {
            return false;
        }

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        return sizeof($monsters) <= 2 &&
               sizeof($thieves) + sizeof($monsters) == sizeof($itemsInPlay);
    }

    /**
     * Must Overrides
     */

    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monstersByGroup = $this->groupByDiceValue($monsters);
        return sizeof($monstersByGroup) == 1;
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monsters = DRItem::setZone($monsters, DR_ZONE_BOX);
        
        $player_id = $this->game->getActivePlayerId();

        $treasures = $this->game->components->getItemsByTypeAndZone(DR_TYPE_TREASURE_TOKEN, DR_ZONE_BOX);
        $treasures = DRUtils::random_item($treasures, sizeof($monsters));
        $treasures = DRItem::setOwner($treasures, $player_id);
        $treasures = DRItem::setZone($treasures, DR_ZONE_INVENTORY);

        for ($i = 0; $i < sizeof($treasures); $i++) {
            $treasures[$i]['from'] = $monsters[$i]['id'];
            $treasures[$i]['previous_zone'] = DR_ZONE_PLAY;
        }

        $this->game->stats->incTreasureOpen(sizeof($treasures));
        $this->game->manager->updateItems($monsters);
        $this->game->manager->updateItems($treasures);
        $this->game->notif->ultimateAmarSuen($monsters, $treasures);

        $this->game->gamestate->nextState('ultimate');
    }

}
