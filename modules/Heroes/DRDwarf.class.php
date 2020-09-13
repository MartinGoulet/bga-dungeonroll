<?php

class DRDwarf extends DRStandardHero
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

    /**
     * States
     */
    function stateBeforeFormingParty(&$dice)
    {
        // Starts with 2 party dice in the graveyard
        $dice[0]['zone'] = ZONE_GRAVEYARD;
        $dice[1]['zone'] = ZONE_GRAVEYARD;
        $this->game->notif->dwarfStartTurn();
    }

    function afterDefeatMonster($party, $monsters)
    {
        $champions = DRUtils::filter($party, 'DRPartyDice::isChampion');

        // Whenever a Champion defeats 2+ Monsters, re-roll it instead of discarding
        if (sizeof($party) == 1 && sizeof($champions) == 1 && sizeof($monsters) >= 2) {
            
            $newDice = DRItem::rollDice($champions);
            $newDice = DRItem::setZone($newDice, ZONE_PARTY);

            $this->game->notif->dwarfRerollChampion($champions, $newDice);
            $this->game->manager->updateItems($newDice);

        }

    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monstersInPlay = DRDungeonDice::getMonsterDices($itemsInPlay);

        $items = $this->game->components->getActivePlayerUsableItems();
        $champions = DRUtils::filter($items, 'DRPartyDice::isChampion');

        return sizeof($monstersInPlay) > 0 && sizeof($monstersInPlay) <= sizeof($champions);
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monstersInPlay = DRDungeonDice::getMonsterDices($itemsInPlay);

        $items = $this->game->components->getActivePlayerUsableItems();
        $champions = DRUtils::filter($items, 'DRPartyDice::isChampion');

        // Discard Monsters
        $monstersInPlay = DRItem::setZone($monstersInPlay, ZONE_BOX);
        
        // Reroll Champions
        $newDice = DRItem::rollDice($champions);
        $newDice = DRItem::setZone($newDice, ZONE_PARTY);

        $this->game->notif->ultimateDwarf($monstersInPlay);
        $this->game->notif->dwarfRerollChampion($champions, $newDice);

        $updateItems = array_merge($monstersInPlay, $newDice);
        $this->game->manager->updateItems($updateItems);

        $this->game->gamestate->nextState('ultimate');
    }
}
