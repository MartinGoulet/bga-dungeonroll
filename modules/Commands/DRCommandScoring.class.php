<?php

class DRCommandScoring extends DRCommand
{

    public function __construct($game, $command_info)
    {
        parent::__construct($game, $command_info);
    }

    public function getAllowedStates()
    {
        return array('regroupPhase');
    }

    public function execute($sub_command_id)
    {
        $players = $this->game->getPlayerScoringInfo();

        $heroes_info = $this->game->components->getHeroesByPlayer();
        $heroes = [];
        foreach ($heroes_info as $player_id => $info) {
            $heroes[$player_id] = DRHeroesManager::getHero($info, $this->game);
        };

        $rowHeader = array(array('str' => clienttranslate('Points'), 'args' => array(), 'type' => 'header'));
        $rowLevel = array(array('str' => clienttranslate('Levels completed'), 'args' => array()));
        $rowDragon = array(array('str' => clienttranslate('Dragon killed'), 'args' => array()));
        $rowTreasure = array(array('str' => clienttranslate('Remaining treasures'), 'args' => array()));
        $rowScales = array(array('str' => clienttranslate('Dragon Scales'), 'args' => array()));
        $rowTownPortal = array(array('str' => clienttranslate('Town Portal'), 'args' => array()));
        $rowTotal = array(array('str' => clienttranslate('Total'), 'args' => array()));

        $isArchaeologistPresent = false;

        foreach ($players as $player_id => $player) {

            $rowHeader[] = array(
                'str' => '${player_name}',
                'args' => array('player_name' => $player['player_name']),
                'type' => 'header'
            );

            $treasures = $this->game->components->getItemsByPlayerAndType($player_id, DR_TYPE_TREASURE_TOKEN);
            $treasures = DRUtils::filter($treasures, function ($token) {
                return $token['zone'] == DR_ZONE_INVENTORY;
            });

            $townPortal = DRItem::getSameAs($treasures, DRTreasureToken::getToken(DR_TOKEN_TOWN_PORTAL));
            $dragonScales = DRItem::getSameAs($treasures, DRTreasureToken::getToken(DR_TOKEN_DRAGON_SCALES));

            $nbrLevel = $this->game->stats->getLevelCompleted($player_id);
            if ($player_id == $this->game->getActivePlayerId()) {
                $nbrLevel += $this->game->vars->getDungeonLevel();
            }

            $isArchaeologist = 
                $heroes[$player_id] instanceof DRArchaeologist || 
                $heroes[$player_id] instanceof DRTombRaider;
            
            $isArchaeologistPresent = $isArchaeologistPresent || $isArchaeologist;

            $isLeprechaun = 
                $heroes[$player_id] instanceof DRLeprechaun || 
                $heroes[$player_id] instanceof DRClurichaun;

            if($isLeprechaun) {
                $nbrTreasures = 0;
                $nbrXpTownPortal = 0;
                $nbrXpDragonScales = 0;
            } else {
                $nbrTreasures = sizeof($treasures) - sizeof($townPortal) - sizeof($dragonScales);
                $nbrXpTownPortal = sizeof($townPortal) * 2;
                $nbrXpDragonScales = intdiv(sizeof($dragonScales), 2) * 2 + sizeof($dragonScales);
            }

            $rowLevel[] = $this->game->getValueWithStar($nbrLevel, true);
            $rowDragon[] = $this->game->getValueWithStar($this->game->stats->getExpDragon($player_id));
            $rowTreasure[] = $this->game->getValueWithStar($nbrTreasures, $isLeprechaun, false, $isLeprechaun);
            $rowScales[] = $this->game->getValueWithStar($nbrXpDragonScales, $isLeprechaun, false, $isLeprechaun);
            $rowTownPortal[] = $this->game->getValueWithStar($nbrXpTownPortal, $isLeprechaun, false, $isLeprechaun);

            $total =
                $nbrLevel +
                $this->game->stats->getExpDragon($player_id) +
                $nbrTreasures +
                $nbrXpDragonScales +
                $nbrXpTownPortal;

            $rowTotal[] = $this->game->getValueWithStar($total, true, $isArchaeologist, false);
        }

        $table = array($rowHeader, $rowLevel, $rowDragon, $rowScales, $rowTownPortal, $rowTreasure, $rowTotal);

        $str = '<i class="fa fa-star tiebreaker"></i> : ${tiebreaker_tr} : ${reminder_tr}';
        if($isArchaeologistPresent) {
            $str .= '<p>${warning_icon} : ${warning_text}</p>';
        }

        $this->game->notifyPlayer($this->game->getActivePlayerId(), "tableWindow", '', array(
            "id" => 'finalScoring',
            "title" => clienttranslate("Scores"),
            "table" => $table,
            "header" => array(
                'str' => $str,
                'args' => array(
                    'tiebreaker_tr' => clienttranslate("Tiebreaker"),
                    'reminder_tr' => clienttranslate("The player with the fewest Treasure tokens is the winner"),
                    'warning_icon' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>',
                    'warning_text' => 'Value may not be final since the player must discard 6 Treasure Tokens at game end.',
                ),
            ),
            "closing" => clienttranslate("Close"),
        ));
    }
}
