<?php

class DRArchaeologist extends DRStandardHero
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

    function getNumberTreasureTokenToDiscard()
    {
        if($this->game->vars->getChooseDieState() == "nextPlayer") {
            // At game end, he must discard 6 treasures;
            return 6;
        } else {
            return 2;
        }
    }

    public function getUltimateAllowedStates() {
        return array('monsterPhase', 'lootPhase', 'dragonPhase', 'regroupPhase');
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
        // When forming the party, draw 2 Treasures Tokens.      
        $treasures = $this->drawTreasures(2);
        $this->game->notif->archaeologistDrawTreasure($treasures);

        $this->game->vars->setChooseDieState($this->getState());
        $this->game->gamestate->nextState('discardTreasures');
    }

    /**
     * States
     */

    function stateAfterFormingParty(&$dice)
    {
        // When forming the party, draw 2 Treasures Tokens.      
        $treasures = $this->drawTreasures(2);
        $this->game->notif->archaeologistDrawTreasure($treasures);
    }

    function statePreNextPlayer()
    {
        if ($this->game->isLastTurn()) {
            $this->game->vars->setChooseDieState("nextPlayer");
            $this->game->notif->archaeologistDiscard();
            return "discardTreasures";
        } else {
            return parent::statePreNextPlayer();
        }
    }


    function drawTreasures($nbr)
    {
        $treasures = $this->game->components->getItemsByTypeAndZone(TYPE_TREASURE_TOKEN, ZONE_BOX);
        $treasures = DRUtils::random_item($treasures, $nbr);
        $treasures = DRItem::setOwner($treasures, $this->game->getActivePlayerId());
        $treasures = DRItem::setZone($treasures, ZONE_INVENTORY);
        $this->game->manager->updateItems($treasures);
        return $treasures;
    }
}
