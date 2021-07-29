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
    function getTotalPartyDice() {
        return 7;
    }

    function getCompanionCountDefeatDragon()
    {
        return 3;
    }

    function getNumberOfDragonRequiredForDragonPhase()
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

    function isChampionKillMultipleMonsters()
    {
        return true;
    }

    function isChampionOpenMultipleChests()
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

    function statePreMonsterPhase()
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

    function afterOpenChest()
    {

    }

    function afterQuaffPotion()
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

    protected function groupByDiceValue($dice) {
        $arr = array();

        foreach ($dice as $item) {
            $arr[$item['value']][] = $item;
        }

        return $arr;
    }
    protected function drawTreasures($nbr)
    {
        $treasures = $this->game->components->getItemsByTypeAndZone(DR_TYPE_TREASURE_TOKEN, DR_ZONE_BOX);
        $treasures = DRUtils::random_item($treasures, $nbr);
        $treasures = DRItem::setOwner($treasures, $this->game->getActivePlayerId());
        $treasures = DRItem::setZone($treasures, DR_ZONE_INVENTORY);
        $this->game->manager->updateItems($treasures);
        return $treasures;
    }
}
