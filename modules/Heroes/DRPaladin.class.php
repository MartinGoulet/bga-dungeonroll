<?php

class DRPaladin extends DRCrusader
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

    function updatePossibleActionAfterUltimate()
    {
        // Because we move to the quaff state, we don't need to update possible actions.
        return false;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $tokens = DRTreasureToken::getTreasureTokens($itemsInPlay);
        return in_array($this->getState(), array('monsterPhase', 'lootPhase', 'dragonPhase')) &&
            sizeof($tokens) == 1;
    }

    function executeUltimate($sub_command_id)
    {
        $this->game->notif->ultimatePaladin();

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);

        // Discard 1 token
        $tokens = DRTreasureToken::getTreasureTokens($itemsInPlay);
        $tokens = DRItem::setZone($tokens, DR_ZONE_BOX);
        $this->game->manager->updateItems($tokens);
        $this->game->NTA_itemMove($tokens);

        // Move all Party dice to the party area (conflict with open chest and quaff potion)
        $party = DRPartyDice::getPartyDice($itemsInPlay);
        if (sizeof($party) > 0) {
            $party = DRItem::setZone($party, DR_ZONE_PARTY);
            $this->game->manager->updateItems($party);
            $this->game->NTA_itemMove($party);
        }

        $items = $this->game->components->getActivePlayerUsableItems();

        // Defeat all Monsters
        $monsters = DRDungeonDice::getMonsterDices($items);
        if (sizeof($monsters) > 0) {
            $monsters = DRItem::setZone($monsters, DR_ZONE_BOX);
            $this->game->manager->updateItems($monsters);
            $this->game->NTA_itemMove($monsters);
        }

        // Discard all dice in the Dragon's lair
        $dragons  = DRUtils::filter($items, 'DRDungeonDice::isDragon');
        if (sizeof($dragons) > 0) {
            $dragons   = DRItem::setZone($dragons, DR_ZONE_BOX);
            $this->game->manager->updateItems($dragons);
            $this->game->NTA_itemMove($dragons);
        }

        // Open all Chests
        $chests = DRUtils::filter($items, 'DRDungeonDice::isChest');
        if (sizeof($chests) > 0) {
            // Move all chests to the playing zone
            $chests = DRItem::setZone($chests, DR_ZONE_PLAY);
            $this->game->manager->updateItems($chests);
            $this->game->NTA_itemMove($chests);

            $commandChest = $this->game->commands->getCommandByName('openChest');
            $commandChest->execute(0);
        }

        // Quaff all potions
        $potions = DRUtils::filter($items, 'DRDungeonDice::isPotion');
        if (sizeof($potions) > 0) {
            // Move all potions to the playing zone
            $potions = DRItem::setZone($potions, DR_ZONE_PLAY);
            $this->game->manager->updateItems($potions);
            $this->game->NTA_itemMove($potions);

            $commandChest = $this->game->commands->getCommandByName('quaffPotion');
            $commandChest->execute(0);
        } else {
            $this->game->notif->updatePossibleActions();
        }
    }
}
