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

    function changeChestToPotion($dice)
    {
        $message = clienttranslate('${player_name} changes ${nbr} Chest(s) into Potion(s) with their hero');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'nbr' => sizeof($dice),
            'items' => $dice
        ]);
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

    function changeSkeletonToPotion($dice)
    {
        $message = clienttranslate('${player_name} changes ${nbr} Skeleton(s) into Potion(s) with their hero');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'nbr' => sizeof($dice),
            'items' => $dice
        ]);
    }

    function fleeDungeon()
    {
        $message = clienttranslate('${player_name} decides to flee the dungeon');
        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
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

    function defeatDragon($with, $treasures)
    {
        $message = clienttranslate('${player_name} defeat the Dragon with ${items_log}, receive 1 Experience and get ${items_log_1} in reward');

        $this->game->notifyAllPlayers("message", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => $with,
            'items_log_1' => $treasures,
        ]);
    }

    function defeatMonsters($monsters, $with)
    {
        $message = clienttranslate('${player_name} defeat ${items_log} with ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => $monsters,
            'items_log_1' => $with,
            'items' => array_merge($monsters, $with),
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

    function openChest($tokens)
    {
        $message = clienttranslate('${player_name} opens Chest and get ${items_log}');

        $this->game->notifyAllPlayers("onNewTokens", $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'tokens' => $tokens,
            'items_log' => $tokens,
        ));
    }

    function quaffPotion($items, $nbrPotions)
    {
        $message = clienttranslate('${player_name} quaffs ${nbr} Potion(s)');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $items,
            'nbr' => $nbrPotions,
        ));
    }

    function revivePartyDice($items)
    {
        $message = clienttranslate('${player_name} revive a ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $items,
            'items_log' => $items,
        ));
    }
    

    function retireTavern()
    {
        $message = clienttranslate('${player_name} decides to retire at the tavern');
        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
        ]);
    }

    function rerollPartyDice($diceBefore, $diceAfter)
    {
        $message = clienttranslate('${player_name} re-rolls ${items_log} and get ${items_log_1}');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $diceAfter,
            'items_log' => $diceBefore,
            'items_log_1' => $diceAfter,
        ]);
    }

    function rollPartyDice($dice)
    {
        $message = clienttranslate('${player_name} rolls ${items_log}');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $dice,
            'items_log' => $dice,
        ]);
    }

    function rollingDungeonDice($dice)
    {
        $message = clienttranslate('${player_name} encounter ${items_log}');
        // Notify the discard of the scroll
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $dice,
            'items_log' => $dice,
        ));
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

    function useDragonBait($items)
    {
        $message = clienttranslate('${player_name} uses Dragon Bait to transform ${nbr} Monster(s) into Dragon');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'nbr' => sizeof($items),
        ]);
    }

    function useRingInvisibility()
    {
        $message = clienttranslate('${player_name} uses Ring of Invisibility to remove all Dragon dice');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
        ]);
    }

    function useTownPortal()
    {
        $message = clienttranslate('${player_name} uses Town Portal to leave the dungeon');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
        ]);
    }

    function useScroll($scrolls, $otherDice)
    {
        $message = clienttranslate('${player_name} uses a scroll to re-rolls ${nbr} dice');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => array_merge($scrolls, $otherDice),
            'nbr' => sizeof($otherDice)
        ]);
    }


    /**
     * Ultimates
     */

    function heroUltimate()
    {
        $this->game->notifyAllPlayers("onHeroUltimate", "", []);
    }

    function ultimateBattlemage($dungeon_dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and discard all dungeon dice');

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
        $message = clienttranslate('${player_name} uses ${hero_name} and transform ${nbr} monster(s) into 1 Potion');

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
        $message = clienttranslate('${player_name} uses ${hero_name} and transforms ${nbr} Monster(s) to Dragon');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dragons,
            'nbr' => sizeof($dragons)
        ]);
    }

    function ultimateMercenary($monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and kills ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $monsters,
            'items_log' => $monsters,
        ]);
    }
    
    function ultimateDiscardDragon($dragons)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and discard all dice from the Dragon\'s Lair');

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
