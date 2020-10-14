<?php

class DRBerserker extends DRDwarf
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
        return false;
    }

    function canRetire()
    {
        if($this->game->vars->getIsHeroActivated() == false) {
            return true;
        }

        if($this->game->vars->getIsBerserkerUltimate() == false) {
            return true;
        }

        return $this->game->vars->getIsDragonKilledThisTurn() ||
               $this->game->vars->getDungeonLevel() == 10;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD);
        return sizeof($items) > 0;
    }

    function executeUltimate($sub_command_id)
    {
        // Roll X Party dice from the Graveyard and add it to your Party
        $dice = $this->game->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD);
        $dice = array_slice($dice, 0, 4);
        $dice = DRItem::rollDice($dice);
        $dice = DRItem::setZone($dice, ZONE_PARTY);

        $this->game->manager->updateItems($dice);

        $this->game->notif->ultimateBerserker($dice);
        $this->game->vars->setIsBerserkerUltimate(true);

        $this->game->gamestate->nextState('ultimate');
    }

}
