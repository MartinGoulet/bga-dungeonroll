<?php

class DRHalfGoblin extends DRStandardHero
{
    public function __construct($game, $cardinfo)
    {
        parent::__construct($game, $cardinfo);
    }

    /**
     * Game breaking rule
     */
    function canLootDuringMonsterPhase() {
        return true;
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
        // At least 1 goblin in the playing area
        return sizeof($this->getGoblinsPlayingArea()) >= 1;
    }

    function executeUltimate($sub_command_id)
    {
        // Get the first goblin
        $goblins = array_slice($this->getGoblinsPlayingArea(), 0, $this->getNumberGoblinsToThief());

        // Return it to the box
        $goblins = DRItem::setZone($goblins, ZONE_BOX);
        $this->game->manager->updateItems($goblins);

        $thieves = $this->getThieveDice(sizeof($goblins));

        $this->game->notif->ultimateHalfGoblin($thieves, $goblins);

        $this->game->gamestate->nextState('ultimate');
    }

    protected function getNumberGoblinsToThief() {
        return 1;
    }

    protected function getThieveDice($nbr) {
        $thieves = array();
        for ($i = 0; $i < $nbr; $i++) {
            $dieThief = $this->getDieThief();
            $this->game->manager->insertTemporaryItem($dieThief);
            $thieves[] = $dieThief;
        }
        return $thieves;
    }

    /**
     * Help method
     */
    private function getDieThief()
    {
        // Create a temporary dice as a thief
        $dieThief = DRPartyDice::getDie(DIE_THIEF);
        $dieThief['owner'] = $this->game->getActivePlayerId();
        $dieThief['zone'] = ZONE_PLAY;
        return $dieThief;
    }

    private function getGoblinsPlayingArea()
    {
        // Get items in the playing area
        $itemsInPlay = $this->game->components->getActivePlayerItemsByZone(ZONE_PLAY);
        // Return goblins in the playing area
        return DRUtils::filter($itemsInPlay, 'DRDungeonDice::isGoblin');
    }
}
