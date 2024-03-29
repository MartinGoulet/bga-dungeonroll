<?php

class DRCommandUseScroll extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        $items = $this->game->components->getActivePlayerUsableItems();
        $scrolls = DRUtils::filter($items, 'DRItem::isScroll');
        if(sizeof($scrolls) == 0) {
            return array();
        }
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $itemsInPlay = $this->getItemsInPlay();
        // Get Scrolls (Dice & Tokens)
        $scrollsInPlay = DRUtils::filter($itemsInPlay, function ($item) {
            return DRPartyDice::isScroll($item) || DRTreasureToken::isScroll($item);
        });
        // Try to find token who is not a scroll (not possible to roll token)
        $nonTreasureScroll = DRUtils::filter($itemsInPlay, function ($item) {
            return DRItem::isTreasureToken($item) && !DRTreasureToken::isScroll($item);
        });
        $itemsTemporary = DRUtils::filter($itemsInPlay, function($item) {
            return DRItem::isTemporaryItem($item) || DRItem::isTemporaryAbility($item);
        });
        // True if at least 1 scroll in play with another item who is not a treasure token
        // except only 1 scroll token.
        return sizeof($scrollsInPlay) >= 1 && 
               sizeof($itemsInPlay) > 1 && 
               sizeof($nonTreasureScroll) == 0 &&
               sizeof($itemsTemporary) == 0;
    }

    public function execute($sub_command_id)
    {
        // Get all items from play
        $items = $this->getItemsInPlay();
        // Get first scroll (try to select token first);
        $scrolls = DRUtils::filter($items, 'DRTreasureToken::isScroll');
        // If no scroll token found, get the first scroll die
        if (sizeof($scrolls) == 0) {
            $scrolls = DRUtils::filter($items, 'DRPartyDice::isScroll');
        }
        // Select the first scroll
        $scroll = $scrolls[0];
        // Seperate the scroll from the other items
        $otherItems = DRItem::excludeItem($items, $scroll);

        $otherItems = DRUtils::filter($otherItems, function($item) {
            return DRItem::isPartyDie($item) || (DRItem::isDungeonDie($item) && !DRDungeonDice::isDragon($item));
        });

        // Reroll other dice
        $rolledDice = DRItem::rollDice($otherItems);

        // Move dice the the right zone
        $dragons = DRDungeonDice::getDragonDice($rolledDice);
        $dragons = DRItem::setZone($dragons, DR_ZONE_DRAGON_LAIR);

        $partys = DRPartyDice::getPartyDice($rolledDice);
        $partys = DRItem::setZone($partys, DR_ZONE_PARTY);

        $dungeons = DRDungeonDice::getDungeonDiceWithoutDragon($rolledDice);
        $dungeons = DRItem::setZone($dungeons, DR_ZONE_DUNGEON);

        // Move the scroll the the graveyard if dice, remove if it's a token
        if (DRItem::isPartyDie($scroll)) {
            $scrolls = DRItem::setZone(array($scroll), DR_ZONE_GRAVEYARD);
        } else {
            $scrolls = DRItem::setZone(array($scroll), null);
        }

        $rolledDice = array_merge($dragons, $partys, $dungeons);

        $hero = $this->game->components->getActivePlayerHero();

        $itemsUpdate = array_merge($scrolls, $rolledDice);
        $this->game->manager->updateItems($itemsUpdate);

        $this->game->notif->useScroll($scrolls, $otherItems, $rolledDice);

        $hero->actionAfterRollingDiceWithScroll($rolledDice);

        $this->game->notif->updatePossibleActions();

        $this->game->gamestate->nextState('scroll');
    }

    function getItemsInPlay()
    {
        return $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
    }

    function getOneScrollInPlay()
    {
        $items = $this->getItemsInPlay();
        // Try to get the token first
        $tokenScroll = DRItem::getSameAs($items, DRTreasureToken::getToken(DR_TOKEN_SCROLL));
        if (sizeof($tokenScroll) > 0) {
            return $tokenScroll[0];
        }
        // Try to get one scroll die if no token found
        $dieScroll = DRItem::getSameAs($items, DRPartyDice::getDie(DR_DIE_SCROLL));
        if (sizeof($dieScroll) > 0) {
            return $dieScroll[0];
        }
        return null;
    }
}
