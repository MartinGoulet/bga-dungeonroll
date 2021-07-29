<?php

class DRKnight extends DRStandardHero
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
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        // True if at least 1 monster
        $items = $this->game->components->getActivePlayerUsableItems();
        $monsters = DRDungeonDice::getMonsterDices($items);
        return sizeof($monsters) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $monsters = DRDungeonDice::getMonsterDices($items);
        $dragons = $this->game->transformMonstersToDragons($monsters);

        $this->game->manager->updateItems($dragons);
        $this->game->notif->ultimateKnightDragonSlayer($dragons, $monsters);

        $this->game->gamestate->nextState('ultimate');
    }

    /**
     * States
     */

    function stateAfterFormingParty(&$dice)
    {
        // All Chests become Potions
        $scrolls = DRItem::getSameAs($dice, DRPartyDice::getDie(DR_DIE_SCROLL));
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRPartyDice::isScroll', DR_DIE_CHAMPION);
        if (sizeof($changes)) {
            $this->game->notif->changeScrollToChampion($changes, $scrolls);
        };
        
    }
}
