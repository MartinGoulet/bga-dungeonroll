<?php

class DRCommandFightMonster extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates() {
        return array('monsterPhase');
    }

    public function canExecute()
    {
        if (!parent::canExecute()) return false;

        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        
        if(sizeOf($monsters) == 0) {
            return false;
        }
        
        $hero = $this->game->components->getActivePlayerHero();
        if ($hero->canDefeatMonster()) {
            return true;
        }

        // No hero can defeat monster with non token companion
        $nonTokenCompanion = DRUtils::filter($itemsInPlay, function ($item) {
            return DRItem::isTreasureToken($item) && !DRItem::isCompanionToken($item);
        });

        if(sizeof($nonTokenCompanion) > 0) {
            return false;
        }

        // Some hero can use scroll (Enchantress and Alexandra) 
        // This verification has been place after checking if hero can defeat monster
        // Note : Some heroes have to check for this verification internally
        $scrolls = DRUtils::filter($itemsInPlay, 'DRItem::isScroll');
        if(sizeof($scrolls) > 0) {
            return false;
        }

        return DRDungeonDice::getMonsterTypeCount($monsters) == 1 &&
            self::getMonsterFightResult($itemsInPlay, $hero);
    }

    public function execute($sub_command_id)
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $companions = DRPartyDice::getPartyDice($itemsInPlay);
        $tokens = DRTreasureToken::getTreasureTokens($itemsInPlay);

        $monsters = DRItem::setZone($monsters, DR_ZONE_BOX);
        $companions = DRItem::setZone($companions, DR_ZONE_GRAVEYARD);
        $tokens = DRItem::setZone($tokens, DR_ZONE_BOX);

        foreach ($companions as &$companion) {
            if(DRItem::isTemporaryAbility($companion) || DRItem::isTemporaryItem($companion)) {
                $companion['zone'] = DR_ZONE_BOX;
            }
        }

        $items = array_merge($companions, $monsters, $tokens);
        $this->game->manager->updateItems($items);
        $this->game->notif->defeatMonsters($items, $monsters, array_merge($companions, $tokens));

        $hero = $this->game->components->getActivePlayerHero();
        $hero->afterDefeatMonster(array_merge($companions, $tokens), $monsters);

        $this->game->gamestate->nextState('fight');
    }

    static function getMonsterFightResult($items, $hero)
    {

        $monsterDices = DRDungeonDice::getMonsterDices($items);
        $companionDices = array_merge(
            DRPartyDice::getCompanionDice($items),
            DRTreasureToken::getTreasureTokens($items)
        );

        $nbTypeMonster = DRDungeonDice::getMonsterTypeCount($monsterDices);

        if (sizeof($monsterDices) == 0) {
            return false;
        } else if ($nbTypeMonster > 1) {
            return false;
        }

        if (sizeOf($companionDices) < sizeof($monsterDices)) {

            if (sizeOf($companionDices) == 1) {

                // Champion kill every type of monsters
                if (DRPartyDice::isChampion($companionDices[0])) {
                    return $hero->isChampionKillMultipleMonsters();
                }
                // Fighter kill all goblins (or vorpal sword)
                if (DRItem::isFighter($companionDices[0]) && DRDungeonDice::isGoblin($monsterDices[0])) {
                    return true;
                }
                // Mage kill all oozes
                if (DRItem::isMage($companionDices[0]) && DRDungeonDice::isOoze($monsterDices[0])) {
                    return true;
                }
                // Cleric kill all skeletons
                if (DRItem::isCleric($companionDices[0]) && DRDungeonDice::isSkeleton($monsterDices[0])) {
                    return true;
                }
            }

            // Not enough compagnion for the fight
            return false;
        } else if (sizeOf($companionDices) > sizeof($monsterDices)) {
            return false;
        }

        return true;
    }
}
