<?php

class DRCommandQuaffPotion extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        $allowedStates = array('lootPhase');

        $hero = $this->game->components->getActivePlayerHero();
        if ($hero instanceof DRHalfGoblin) {
            $allowedStates[] = 'monsterPhase';
        }

        return $allowedStates;
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        // Get all items from play
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        // Get chests items
        $potions = DRItem::getSameAs($itemsInPlay, DRDungeonDice::getDie(DR_DIE_POTION));
        // Get any party dice or "companion" tokens or scroll.
        $party_items = array_values(array_filter($itemsInPlay, function ($item) {
            return DRItem::isPartyDie($item) || 
                   DRItem::isCompanionToken($item) ||
                   DRTreasureToken::isScroll($item);
        }));

        if (sizeof($potions) == 0) {
            return false;
        } else if (sizeof($party_items) != 1) {
            return false;
        }

        return true;
    }

    public function execute($sub_command_id)
    {
        // Get all items from play
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        // Get chests items
        $potions = DRItem::getSameAs($itemsInPlay, DRDungeonDice::getDie(DR_DIE_POTION));

        DRUtils::userAssertTrue(
            clienttranslate('Not enough dice in your graveyard for the number of potions you want to quaff.'),
            $this->hasEnoughDiceInGraveyard($itemsInPlay, $potions)
        );

        // Get any party dice or "companion" tokens or scroll.
        $party_items = array_values(array_filter($itemsInPlay, function ($item) {
            return DRItem::isPartyDie($item) || 
                   DRItem::isCompanionToken($item) ||
                   DRTreasureToken::isScroll($item);
        }));

        // Move dice to graveyard
        $partyDice = DRItem::setZone(DRPartyDice::getPartyDice($party_items), DR_ZONE_GRAVEYARD);
        // Return token
        $tokens = DRItem::setZone(DRTreasureToken::getTreasureTokens($party_items), DR_ZONE_BOX);
        // Return potions to the box
        $potions = DRItem::setZone($potions, DR_ZONE_BOX);

        // Update database
        $itemsUpdate = array_merge($partyDice, $tokens, $potions);
        $this->game->manager->updateItems($itemsUpdate);
        // Notify for the move
        $this->game->notif->quaffPotion($itemsUpdate, $potions);

        // Set the number of die to retrieve from the graveyard
        $this->game->vars->setChooseDieCount(sizeof($potions));
        $this->game->vars->setChooseDieState($this->getState());

        $hero = $this->game->components->getActivePlayerHero();
        $hero->afterQuaffPotion();

        // Move to next state
        $this->game->gamestate->nextState('chooseDie');

    }

    function hasEnoughDiceInGraveyard($itemsInPlay, $potions) 
    {
        $party_dice_play_zone = DRUtils::filter(DRPartyDice::getPartyDice($itemsInPlay), function($item) {
            return !DRItem::isTemporaryAbility($item) && !DRItem::isTemporaryItem($item);
        });     

        $items_graveyard = DRPartyDice::getPartyDice($this->game->components->getActivePlayerItemsByZone(DR_ZONE_GRAVEYARD));

        return sizeof($party_dice_play_zone) + sizeof($items_graveyard) >= sizeof($potions);
    }
}
