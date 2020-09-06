<?php

class DROccultist extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    /**
     * Game breaking rules
     */
    function canDefeatMonster()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $clerics = DRUtils::filter($itemsInPlay, 'DRItem::isCleric');
        $mages = DRUtils::filter($itemsInPlay, 'DRItem::isMage');

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);
        $skeletons = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isSkeleton');
        $oozes = DRUtils::filter($itemsInPlay, 'DRDungeonDice::isOoze');

        // Clerics may be used as Mages and Mages may be used as Clerics.
        if (sizeof($clerics) == 1 && sizeof($oozes) == sizeof($monsters))
            return true;

        if (sizeof($mages) == 1 && sizeof($skeletons) == sizeof($monsters))
            return true;

        return false;
    }

    /**
     * Game breaking rules
     */
    function canDefeatDragon()
    {
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);

        $companions = DRItem::getCompanions($itemsInPlay);
        $clerics = DRUtils::filter($companions, 'DRItem::isCleric');
        $mages = DRUtils::filter($companions, 'DRItem::isMage');

        if (sizeof($companions) == 3) {
            if (sizeof($clerics) == 2 && sizeof($mages) == 0)
                return true;
            elseif (sizeof($clerics) == 0 && sizeof($mages) == 2)
                return true;
        }

        return false;

    }

    function canLevelUp()
    {
        return true;
    }

    /**
     * Must Overrides
     */
    function canExecuteUltimate()
    {
        // At least 1 skeleton in the playing area
        return sizeof($this->getSkeletonsPlayingArea()) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        // Get the first skeleton
        $skeletons = array_slice($this->getSkeletonsPlayingArea(), 0, $this->getNumberSkeletonToFighter());

        // Return it to the box
        $skeletons = DRItem::setZone($skeletons, ZONE_BOX);
        $this->game->manager->updateItems($skeletons);

        $fighters = $this->getFighterDice(sizeof($skeletons));

        $this->game->notif->ultimateOccultist($fighters, $skeletons);

        $this->game->gamestate->nextState('ultimate');
    }

    protected function getNumberSkeletonToFighter() {
        return 1;
    }

    protected function getFighterDice($nbr) {
        $fighters = array();
        for ($i = 0; $i < $nbr; $i++) {
            $dieFighter = $this->getDieFighter();
            $this->game->manager->insertTemporaryItem($dieFighter);
            $fighters[] = $dieFighter;
        }
        return $fighters;
    }

    /**
     * Help method
     */
    private function getDieFighter()
    {
        // Create a temporary dice as a fighter
        $die = DRPartyDice::getDie(DIE_FIGHTER);
        $die['owner'] = $this->game->getActivePlayerId();
        $die['zone'] = ZONE_PLAY;
        return $die;
    }

    private function getSkeletonsPlayingArea()
    {
        // Get items in the playing area
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        // Return skeletons in the playing area
        return DRUtils::filter($itemsInPlay, 'DRDungeonDice::isSkeleton');
    }
}
