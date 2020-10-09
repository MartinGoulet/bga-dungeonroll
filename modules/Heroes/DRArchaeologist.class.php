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
        $this->game->notif->heroDrawTreasure($treasures);

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
        $this->game->notif->heroDrawTreasure($treasures);
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
}
