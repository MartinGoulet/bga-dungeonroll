<?php

class DRStandardHero extends APP_GameClass
{
    protected $game;
    protected $cardinfo;

    public function __construct($game, $cardinfo)
    {
        $this->game = $game;
        $this->cardinfo = $cardinfo;
    }

    public function getCommandText()
    {
        return $this->cardinfo['commandText'];
    }

    public function getCommandSpecialty()
    {
        return $this->cardinfo['commandSpecialty'];
    }

    public function getCommands()
    {
        return $this->cardinfo['commands'];
    }

    public function getName()
    {
        return $this->cardinfo['name'];
    }

    public function getUIData()
    {
        return $this->cardinfo;
    }

    public function getState()
    {
        return $this->game->gamestate->state()['name'];
    }

    public function getDiceForRollDungeonStep()
    {
        return null;
    }

    public function canSkipMonsterPhase() 
    {
        return true;
    }

    public function getUltimateAllowedStates() {
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    public function getSpecialtyAllowedStates() {
        return array();
    }

    /**
     * Game breaking rule
     */
    function getCompanionCountDefeatDragon()
    {
        return 3;
    }

    function getNumberTreasureTokenToDiscard()
    {
        return 0;
    }

    function canDefeatMonster()
    {
        // Use the normal rule for fighting monster.
        return false;
    }

    function canDefeatDragon()
    {
        // Use the normal rule for fighting monster.
        return false;
    }

    function canOpenAllChests()
    {
        // Use the normal rule for fighting monster.
        return false;
    }

    function canLootDuringMonsterPhase()
    {
        return true;
    }

    function canLevelUp()
    {
        return false;
    }

    function updatePossibleActionAfterUltimate()
    {
        return true;
    }

    /**
     * Must Overrides
     */

    function canExecuteUltimate()
    {
        return false;
    }

    function executeUltimate($sub_command_id)
    {
    }

    function canExecuteSpecialty()
    {
        return false;
    }

    function executeSpecialty()
    {
    }

    function canRetire() 
    {
        return true;
    }

    /**
     * States
     */

    function stateBeforeFormingParty(&$dice)
    {

    }

    function stateAfterFormingParty(&$dice)
    {

    }

    function stateAfterDungeonDiceRoll($dice)
    {
        
    }

    function actionAfterRollingDiceWithScroll($dice) 
    {

    }

    function statePreNextPlayer()
    {
        // Move to the next player
        return 'next';
    }

    function afterDragonBait() 
    {
        
    }

    function afterDefeatMonster($party, $monsters)
    {
        
    }

    /**
     * Protected methods
     */



    protected function allDungeonDiceXBecomeY(&$dice, $fncCheckDie, $dieTo)
    {
        // When rolling Dungeon dice, all Chests become Potions
        $changes = array();
        // &$die : Alter the die in parameter
        foreach ($dice as &$die) {
            if ($fncCheckDie($die)) {
                $die['value'] = $dieTo;
                $changes[] = $die;
            }
        }

        // Notify the modification
        if (sizeof($changes) > 0) {
            $this->game->manager->updateItems($dice);
        }
        
        return $changes;
    }
}
