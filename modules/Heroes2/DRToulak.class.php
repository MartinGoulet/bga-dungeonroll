<?php

class DRToulak extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    public function getUltimateAllowedStates()
    {
        return array('monsterPhase');
    }

    public function getSpecialtyAllowedStates()
    {
        return array('monsterPhase');
    }
    
    /**
     * States
     */
    function stateAfterDungeonDiceRoll($dice)
    {
        $this->game->vars->setIsSpecialtyActivated(false);
    }

    /**
     * Must Overrides
     */

    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerUsableItems();
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        return sizeof($monsters) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerUsableItems();
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);

        // Return monsters to the box and notify for the kill
        $monsters = DRItem::setZone($monsters, DR_ZONE_BOX);
        $this->game->manager->updateItems($monsters);
        $this->game->notif->ultimateToulak($monsters);

        $this->game->gamestate->nextState('ultimate');
    }
    
    function canExecuteSpecialty()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        return sizeof($monsters) == 1 && !$this->game->vars->getIsSpecialtyActivated();
    }

    function executeSpecialty()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);

        // Reroll other dice
        $rolledDice = DRItem::rollDice($monsters);

        // Move dice the the right zone
        $dragons = DRDungeonDice::getDragonDice($rolledDice);
        $dragons = DRItem::setZone($dragons, DR_ZONE_DRAGON_LAIR);

        $dungeons = DRDungeonDice::getDungeonDiceWithoutDragon($rolledDice);
        $dungeons = DRItem::setZone($dungeons, DR_ZONE_DUNGEON);

        $rolledDice = array_merge($dragons, $dungeons);

        $this->game->manager->updateItems($rolledDice);
        $this->game->notif->toulakRerollDungeonDie($rolledDice, $monsters);

        $this->game->vars->setIsSpecialtyActivated(true);

        // His ultimate is considered to be a scroll (and can move back to monster phase)
        $this->game->gamestate->nextState('scroll');
    }

}
