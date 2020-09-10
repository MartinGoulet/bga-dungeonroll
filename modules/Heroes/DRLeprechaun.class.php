<?php

class DRLeprechaun extends DRStandardHero
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

    protected function getNumberOfMonsterToTransform()
    {
        return 1;
    }

    /**
     * States
     */
    function stateAfterDungeonDiceRoll($dice)
    {
        $this->applySpecialty($dice);
    }

    function actionAfterRollingDiceWithScroll($dice)
    {
        $this->applySpecialty($dice);
    }

    function statePreNextPlayer()
    {
        if ($this->game->isLastTurn()) {
            // Discard all treasures
            $items = $this->game->components->getActivePlayerUsableItems();
            $tokens = DRTreasureToken::getTreasureTokens($items);
            $tokens = DRItem::setZone($tokens, ZONE_BOX);

            $this->game->manager->updateItems($tokens);
            $this->game->notif->leprechaunEndGame($tokens);
        }

        return parent::statePreNextPlayer();
    }



    function applySpecialty($dice)
    {
        // All Potions become Chests
        $potions = DRItem::getSameAs($dice, DRDungeonDice::getDie(DIE_POTION));
        $changes = $this->allDungeonDiceXBecomeY($dice, 'DRDungeonDice::isPotion', DIE_CHEST);
        if (sizeof($changes)) {
            $this->game->notif->changePotionToChest($changes, $potions);
        };
    }


    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        return sizeof($monsters) <= $this->getNumberOfMonsterToTransform() && sizeof($monsters) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $before = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $monsters = DRItem::setZone($monsters, ZONE_BOX);

        // Change first monster into a potion
        $monsters[0]['zone'] = ZONE_DUNGEON;
        $monsters[0]['value'] = DIE_CHEST;

        // Return potion to the dungeon zone
        $this->game->manager->updateItems($monsters);
        $this->game->notif->ultimateLeprechaun($monsters, $before);

        $this->game->gamestate->nextState('ultimate');
    }
}
