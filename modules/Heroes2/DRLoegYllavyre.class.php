<?php

class DRLoegYllavyre extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    public function getUltimateAllowedStates()
    {
        return array('monsterPhase', 'lootPhase', 'dragonPhase');
    }

    public function getSpecialtyAllowedStates()
    {
        return array('monsterPhase');
    }
    
    function statePreMonsterPhase()
    {

        $items = $this->game->components->getActivePlayerUsableItems();
        $monsters = DRDungeonDice::getMonsterDices($items);
        $monsterGroups = $this->groupByDiceValue($monsters);

        if(sizeof($monsterGroups) == 3 && $this->game->vars->getIsHeroActivated()) {
            $this->game->vars->setIsHeroActivated(false);
            $this->game->notif->refreshLoegYllavyre($monsters);
        }

    }

    function canExecuteSpecialty()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $monster = DRDungeonDice::getMonsterDices($items);
        $scroll = DRUtils::filter($items, function($item) {
            return DRItem::isScroll($item);
        });
        return sizeof($items) == 2 && 
               sizeof($monster) == 1 && 
               sizeof($scroll) == 1;
    }

    function executeSpecialty()
    {
        $items = $this->game->components->getActivePlayerItemsByZone(DR_ZONE_PLAY);
        $originalMonster = DRDungeonDice::getMonsterDices($items);
        $monster = DRDungeonDice::getMonsterDices($items);
        $scroll = DRUtils::filter($items, function($item) {
            return DRItem::isScroll($item);
        });

        $monster[0]['value'] = DR_DIE_DRAGON;
        
        if(DRItem::isPartyDie($scroll[0])) {
            $scroll = DRItem::setZone($scroll, DR_ZONE_GRAVEYARD);
        } else {
            $scroll = DRItem::setZone($scroll, DR_ZONE_BOX);
        }
        $monster = DRItem::setZone($monster, DR_ZONE_DRAGON_LAIR);

        $updateItems = array_merge($scroll, $monster);
        $this->game->manager->updateItems($updateItems);
        $this->game->NTA_itemMove($updateItems);

        $this->game->notif->loegYllavyreTransformDragon($updateItems, $originalMonster);
        $this->game->gamestate->nextState('scroll');
    }
    
    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        // At least 1 dragon on the board
        $items = $this->game->components->getActivePlayerUsableItems();
        $dragon = DRDungeonDice::getDragonDice($items);
        return sizeof($dragon) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        $items = $this->game->components->getActivePlayerUsableItems();
        $dragon = DRDungeonDice::getDragonDice($items);

        // Return them to the box
        $dragon = DRItem::setZone($dragon, DR_ZONE_BOX);
        $this->game->manager->updateItems($dragon);

        $generic_dragon = $this->getDiceGenericDragon(sizeof($dragon));
        
        $this->game->notif->ultimateLoegYllavyre($dragon, $generic_dragon);

        $this->game->gamestate->nextState('ultimate');
    }

    /**
     * Help method
     */
    protected function getDiceGenericDragon($nbr) {
        $companions = array();
        for ($i = 0; $i < $nbr; $i++) {
            $dieGeneric = $this->getDieGenericDragon();
            $this->game->manager->insertTemporaryItem($dieGeneric);
            $companions[] = $dieGeneric;
        }
        return $companions;
    }
    
    private function getDieGenericDragon()
    {
        // Create a temporary dice as a fighter
        $die = DRPartyDice::getDie(DR_DIE_GENERIC_DRAGON);
        $die['owner'] = $this->game->getActivePlayerId();
        $die['zone'] = DR_ZONE_PLAY;
        return $die;
    }

}
