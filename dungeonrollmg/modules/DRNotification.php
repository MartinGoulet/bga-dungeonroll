<?php

define('NOTIF_DICE_ROLL', 'onDiceRolled');
define('NOTIF_ITEM_MOVE', 'onItemsMoved');
define('NOTIF_HERO_LEVEL_UP', 'onHeroLevelUp');

class DRNotification extends APP_GameClass
{
    private $game;
    private $vars;

    function __construct($game, $vars)
    {
        $this->game = $game;
        $this->vars = $vars;
    }

    function changeScrollToChampion($dice)
    {
        $message = clienttranslate('${player_name} changes ${nbr} Scrolls into Champions with their hero');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'nbr' => sizeof($dice),
            'items' => $dice
        ]);
    }

    function heroLevelUp($heroNovice, $heroMaster)
    {
        $message = clienttranslate('${player_name} level up ${hero_novice_name} to ${hero_master_name}');

        $heroNoviceCard = $this->game->card_types["4_" . $heroNovice['value']];
        $heroMasterCard = $this->game->card_types["5_" . $heroMaster['value']];

        $this->game->notifyAllPlayers(NOTIF_HERO_LEVEL_UP, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'player_id' => $this->game->getActivePlayerId(),
            'hero_novice_name' => $heroNoviceCard['name'],
            'hero_master_name' => $heroMasterCard['name'],
            'hero_novice' => $heroNovice,
            'hero_master' => $heroMaster,
        ]);
    }

    function newDelve()
    {
        $message = clienttranslate('${player_name} starts the delve #${delve_number}');

        $this->game->notifyAllPlayers("onNewDelve", $message, [
            'player_id' => $this->game->getActivePlayerId(),
            'player_name' => $this->game->getActivePlayerName(),
            'delve_number' => $this->game->getCurrentDelve()
        ]);
    }

    function newDungeonLevel($level)
    {
        $message = clienttranslate('${player_name} starts the level #${level}');

        $this->game->notifyAllPlayers("onNewLevel", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'level' => $level
        ]);
    }

    function newPlayerTurn()
    {
        $this->game->notifyAllPlayers("onNewPlayerTurn", '', array());
    }

    function newTokens($tokens)
    {
        $this->game->notifyAllPlayers("onNewTokens", '', array('tokens' => $tokens));
    }

    function rollPartyDice($dice)
    {
        $message = clienttranslate('${player_name} rolls all party dice');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $dice
        ]);
    }

    function selectHero($hero)
    {
        $message = clienttranslate('${player_name} select ${hero_name}');
        $this->game->notifyAllPlayers("onSelectHero", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'hero' => $hero
        ]);
    }

    function skipMonsterPhase()
    {
        $message = clienttranslate('${player_name} skip the monster phase because no monster or scroll found');
        $this->game->notifyAllPlayers("message", $message, [
            'player_name' => $this->game->getActivePlayerName()
        ]);
    }

    function skipLootPhase()
    {
        $message = clienttranslate('${player_name} skip the loot phase because no chest or potion found');
        $this->game->notifyAllPlayers("message", $message, [
            'player_name' => $this->game->getActivePlayerName()
        ]);
    }

    function skipDragonPhase()
    {
        $message = clienttranslate('${player_name} skip the dragon phase because no dragon found');
        $this->game->notifyAllPlayers("message", $message, [
            'player_name' => $this->game->getActivePlayerName()
        ]);
    }

    function updatePossibleActions()
    {
        $args = $this->game->argGenericPhasePlayerTurn();
        $this->game->notifyPlayer($this->game->getActivePlayerId(), "updatePossibleActions", '', $args);
    }

    function updatedScores()
    {
        $this->game->notifyAllPlayers("updateScores", '', array(
            'scores' => self::getCollectionFromDB("SELECT player_id, player_score as player_score FROM player", true)
        ));
    }

    function useScroll($scrolls, $otherDice)
    {
        $message = clienttranslate('${player_name} uses a scroll to re-rolls ${nbr} dice.');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => array_merge($scrolls, $otherDice),
            'nbr' => sizeof($otherDice)
        ]);
    }


    /**
     * Ultimates
     */



    function ultimateBattlemage($dungeon_dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and discard all dungeon dice.');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dungeon_dice
        ]);
    }

    function ultimateCommander($dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and reroll ${nbr} dice');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'nbr' => sizeof($dice)
        ]);
    }

    function ultimateCrusader($die)
    {
        if($die['value'] == DIE_FIGHTER) {
            $message = clienttranslate('${player_name} uses ${hero_name} as a Fighter');
        } else {
            $message = clienttranslate('${player_name} uses ${hero_name} as a Cleric');
        }

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => array($die),
        ]);
    }

    function ultimateEnchantressBeguiler($dice, $nbr)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and transform ${nbr} monster(s) into a potion');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'nbr' => $nbr
        ]);
    }

    function ultimateHalfGoblin($dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and transforms 1 Goblin into a Thief');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
        ]);
    }

    function ultimateKnightDragonSlayer($dragons)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and transforms ${nbr} monster(s) to dragon(s)');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dragons,
            'nbr' => sizeof($dragons)
        ]);
    }

    function ultimateMercenary($monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and kills 2 monsters');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $monsters
        ]);
    }
    
    function ultimateMinstrel($dragons)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and discard all dice fromn the Dragon\'s Lair');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dragons
        ]);
    }
    

    function ultimateOccultist($dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and transforms 1 Skeleton into a Fighter');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
        ]);
    }
    
    function ultimatePaladin()
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to defeat all Monsters, open all Chests, quaff all Potions and discard all dice in the Dragon\'s Lair');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
        ]);
    }

    function ultimateSpellSword($die)
    {
        if($die['value'] == DIE_FIGHTER) {
            $message = clienttranslate('${player_name} uses ${hero_name} as a Fighter');
        } else {
            $message = clienttranslate('${player_name} uses ${hero_name} as a Mage');
        }

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => array($die),
        ]);
    }

}
