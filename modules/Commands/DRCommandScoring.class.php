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

        $rowHeader = array(array('str' => clienttranslate('Points'), 'args' => array(), 'type' => 'header'));
        $rowLevel = array(array('str' => clienttranslate('Levels completed'), 'args' => array()));
        $rowDragon = array(array('str' => clienttranslate('Dragon killed'), 'args' => array()));
        $rowTreasure = array(array('str' => clienttranslate('Treasures'), 'args' => array()));
        $rowScales = array(array('str' => clienttranslate('Dragon Scales'), 'args' => array()));
        $rowTownPortal = array(array('str' => clienttranslate('Town Portal'), 'args' => array()));
        $rowTotal = array(array('str' => clienttranslate('Total'), 'args' => array()));

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

            $nbrTreasures = sizeof($treasures);
            $nbrXpTownPortal = sizeof($townPortal);
            $nbrXpDragonScales = intdiv(sizeof($dragonScales), 2) * 2;

            $rowLevel[] = $this->game->getValueWithStar($nbrLevel, true);
            $rowDragon[] = $this->game->getValueWithStar($this->game->stats->getExpDragon($player_id));
            $rowTreasure[] = $this->game->getValueWithStar($nbrTreasures);
            $rowScales[] = $this->game->getValueWithStar($nbrXpDragonScales);
            $rowTownPortal[] = $this->game->getValueWithStar($nbrXpTownPortal);

            $total =
                $nbrLevel +
                $this->game->stats->getExpDragon($player_id) +
                $nbrTreasures +
                $nbrXpDragonScales +
                $nbrXpTownPortal;

            $rowTotal[] = $this->game->getValueWithStar($total, true);
        }

        $table = array($rowHeader, $rowLevel, $rowTreasure, $rowDragon, $rowScales, $rowTownPortal, $rowTotal);

        $this->game->notifyPlayer($this->game->getActivePlayerId(), "tableWindow", '', array(
            "id" => 'finalScoring',
            "title" => clienttranslate("Scores"),
            "table" => $table,
            "header" => array(
                'str' => '<i class="fa fa-star tiebreaker"></i> : ${tiebreaker_tr} : ${reminder_tr}',
                'args' => array(
                    'tiebreaker_tr' => clienttranslate("Tiebreaker"),
                    'reminder_tr' => clienttranslate("The player with the fewest Treasure tokens is the winner"),
                ),
            ),
            "closing" => clienttranslate("Close"),
        ));
    }
}
