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

    function changeChestToPotion($potions, $chests)
    {
        $message = clienttranslate('${player_name} changes ${items_log} into ${items_log_1} with their hero');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $potions,
            'items_log' => $chests,
            'items_log_1' => $potions,
        ]);
    }

    function changePotionToChest($chests, $potions)
    {
        $message = clienttranslate('${player_name} changes ${items_log} into ${items_log_1} with their hero');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $chests,
            'items_log' => $potions,
            'items_log_1' => $chests,
        ]);
    }

    function changeScrollToChampion($dice, $scrolls)
    {
        $message = clienttranslate('${player_name} changes ${items_log} into ${items_log_1} with their hero');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $dice,
            'items_log' => $scrolls,
            'items_log_1' => $dice,
        ]);
    }

    function changeSkeletonToPotion($dice, $skeletons)
    {
        $message = clienttranslate('${player_name} changes ${items_log} into ${items_log_1} with their hero');
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $dice,
            'items_log' => $skeletons,
            'items_log_1' => $dice,
        ]);
    }

    function fleeDungeon()
    {
        $message = clienttranslate('${player_name} decides to flee the dungeon and scores 0 ${experience}');
        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'experience' => clienttranslate('Experience'),
        ]);
    }

    function heroLevelUp($heroNovice, $heroMaster)
    {
        $message = clienttranslate('${player_name} levels up ${hero_novice_name} to ${hero_master_name}');

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
        $message = clienttranslate('${player_name} defeats the Dragon with ${items_log}, receive 1 ${experience} and get ${items_log_1} in reward');

        $this->game->notifyAllPlayers("message", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => $with,
            'items_log_1' => $treasures,
            'experience' => clienttranslate('Experience'),
        ]);
    }

    function defeatMonsters($items, $monsters, $with)
    {
        $message = clienttranslate('${player_name} uses ${items_log} to defeat ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => $with,
            'items_log_1' => $monsters,
            'items' => array_merge($monsters, $with),
        ]);
    }

    function discardTreasure($items)
    {
        $message = clienttranslate('${player_name} discards ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $items,
            'items_log' => $items,
        ]);
    }

    function discardTemporaryItem($items)
    {
        $message = clienttranslate('${player_name} discards ${items_log} during the Regroup Phase');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $items,
            'items_log' => $items,
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

    function openChest($party, $chests, $tokens)
    {

        if (sizeof($party) > 0) {
            $message = clienttranslate('${player_name} uses ${items_log} to open ${items_log_1} and gets ${items_log_2}');
        } else {
            $message = clienttranslate('${player_name} uses ${hero_name} to open ${items_log_1} and gets ${items_log_2}');
        }

        $this->game->notifyAllPlayers("onNewTokens", $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'tokens' => $tokens,
            'items_log' => $party,
            'items_log_1' => $chests,
            'items_log_2' => $tokens,
        ));
    }

    function quaffPotion($items, $potions)
    {
        $message = clienttranslate('${player_name} uses ${items_log} to quaffs ${items_log_1}');

        $use = DRUtils::filter($items, function ($item) {
            return !DRDungeonDice::isPotion($item);
        });

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $items,
            'items_log' => $use,
            'items_log_1' => $potions,
        ));
    }

    function revivePartyDice($items)
    {
        $message = clienttranslate('${player_name} revives a ${items_log}');

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
        $message = clienttranslate('${player_name} encounters ${items_log}');
        // Notify the discard of the scroll
        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $dice,
            'items_log' => $dice,
        ));
    }

    function selectHero($hero)
    {
        $message = clienttranslate('${player_name} selects ${hero_name}');
        $this->game->notifyAllPlayers("onSelectHero", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'hero' => $hero
        ]);
    }

    function sorceressDiscardDragon($dragons)
    {
        $message = clienttranslate('${player_name} discards all Dragon dice because 3 or more dice in the Dragon\'s Lair');
        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $dragons
        ]);
    }

    function updatePossibleActions()
    {
        $args = $this->game->argGenericPhasePlayerTurn();
        $this->game->notifyPlayer($this->game->getActivePlayerId(), "updatePossibleActions", '', $args);
    }

    function updateScorePlayer($nbr)
    {
        $message = clienttranslate('${player_name} scores ${nbr} ${experience}');
        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'nbr' => $nbr,
            'experience' => clienttranslate('Experience'),
        ]);
    }

    function updatedScores()
    {
        $this->game->notifyAllPlayers("updateScores", '', array(
            'scores' => self::getCollectionFromDB("SELECT player_id, player_score as player_score FROM player", true)
        ));
    }

    function useDragonBait($dragonBait, $monsters, $dragons)
    {
        $message = clienttranslate('${player_name} uses ${items_log} to transform ${items_log_1} into ${items_log_2}');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => $dragonBait,
            'items_log_1' => $monsters,
            'items_log_2' => $dragons,
        ]);
    }

    function useElixir($elixirToken)
    {
        $message = clienttranslate('${player_name} uses ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => $elixirToken,
            'items_log' => $elixirToken,
        ]);
    }

    function useRingInvisibility()
    {
        $message = clienttranslate('${player_name} uses ${items_log} to remove all Dragon dice');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => array(DRTreasureToken::getToken(TOKEN_RING_INVISIBILITY)),
        ]);
    }

    function useTownPortal()
    {
        $message = clienttranslate('${player_name} uses ${items_log} to leave the dungeon');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => array(DRTreasureToken::getToken(TOKEN_TOWN_PORTAL)),
        ]);
    }

    function useScroll($scrolls, $beforeRollDice, $afterRollDice)
    {
        $message = clienttranslate('${player_name} uses ${items_log} to re-rolls ${items_log_1} into ${items_log_2}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items' => array_merge($scrolls, $afterRollDice),
            'items_log' => $scrolls,
            'items_log_1' => $beforeRollDice,
            'items_log_2' => $afterRollDice,
        ]);
    }

    /**
     * Specialty
     */

    function archaeologistDiscard()
    {
        $message = clienttranslate('${player_name} must discards 6 Treasures Tokens at the end of the game');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
        ]);
    }

    function dwarfStartTurn() {

        $message = clienttranslate('${player_name} starts with 2 Party dice in the Graveyard');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
        ]);

    }

    function dwarfRerollChampion($champion, $newdice) {

        $message = clienttranslate('${player_name} re-rolls ${items_log} into ${items_log_1} instead of discarding it with the ${hero_name}');

        $this->game->notifyAllPlayers(NOTIF_DICE_ROLL, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $newdice,
            'items_log' => $champion,
            'items_log_1' => $newdice,
        ]);

    }

    function leprechaunEndGame($treasures)
    {
        $message = clienttranslate('${player_name} discards ${items_log} at the end of the game');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $treasures,
            'items_log' => $treasures,
        ]);
    }

    function scoutSelectDungeonDice($dice)
    {
        $message = clienttranslate('${player_name} selects ${items_log} for level #${level}');

        $this->game->notifyAllPlayers('message', $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'items_log' => $dice,
            'level' => sizeof($dice),
        ]);
    }
    
    function toulakRerollDungeonDie($reroll, $monster)
    {
        $message = clienttranslate('${player_name} re-rolls ${items_log} with ${hero_name} and get ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $reroll,
            'items_log' => $monster,
            'items_log_1' => $reroll,
        ]);
    }

    function trackerRerollGoblin($reroll, $goblin)
    {
        $message = clienttranslate('${player_name} re-rolls ${items_log} with ${hero_name} and get ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $reroll,
            'items_log' => $goblin,
            'items_log_1' => $reroll,
        ]);
    }


    function heroDrawTreasure($treasures)
    {
        $message = clienttranslate('${player_name} draws ${items_log} with ${hero_name}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $treasures,
            'items_log' => $treasures,
        ]);
    }

    /**
     * Ultimates
     */

    function refreshCassandra($die)
    {
        $message = clienttranslate('${player_name} refreshes ${hero_name} after discarding ${items_log}');
        $this->game->notifyAllPlayers("onHeroRefresh", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items_log' => array($die),
        ]);
    }

    function refreshSzopin($monsters)
    {
        $message = clienttranslate('${player_name} refreshes ${hero_name} after defeating ${items_log}');
        $this->game->notifyAllPlayers("onHeroRefresh", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items_log' => $monsters,
        ]);
    }
    
    function refreshTristan($treasures)
    {
        $message = clienttranslate('${player_name} refreshes ${hero_name} after discarding ${items_log}');
        $this->game->notifyAllPlayers("onHeroRefresh", $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items_log' => $treasures,
        ]);
    }

    function heroUltimate()
    {
        $this->game->notifyAllPlayers("onHeroUltimate", "", []);
    }

    function ultimateAlchemist($dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to revive a ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'items_log' => $dice,
        ]);
    }

    function ultimateAmarSuen($monsters, $tokens)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to discard ${items_log} and draws ${items_log_1}');

        $this->game->notifyAllPlayers("onNewTokens", $message, array(
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'tokens' => $tokens,
            'items_log' => $monsters,
            'items_log_1' => $tokens,
        ));
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

    function ultimateBerserker($dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to roll ${items_log} from the Graveyard and add them to the Party');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'items_log' => $dice,
        ]);
    }

    function ultimateCassandra($dungeon_dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and discards ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dungeon_dice,
            'items_log' => $dungeon_dice,
        ]);
    }

    function ultimateCommander($dice, $before)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to re-roll ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'items_log' => $before,
            'items_log_1' => $dice,
        ]);
    }

    function ultimateCrusader($die)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} as a ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => array($die),
            'items_log' => array($die),
        ]);
    }

    function ultimateDwarf($monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to discard ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $monsters,
            'items_log' => $monsters,
        ]);
    }

    function ultimateEnchantressBeguiler($dice, $diceBefore)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to transform ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'items_log' => $diceBefore,
            'items_log_1' => array(DRDungeonDice::getDie(DIE_POTION)),
        ]);
    }

    function ultimateGuildLeader($before, $after)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to transform ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $after,
            'items_log' => $before,
            'items_log_1' => $after,
        ]);
    }

    function ultimateHalfGoblin($thieves, $goblins)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to transform ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => array_merge($thieves, $goblins),
            'items_log' => $goblins,
            'items_log_1' => $thieves,
        ]);
    }

    function ultimateKnightDragonSlayer($dragons, $monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to transform ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dragons,
            'items_log' => $monsters,
            'items_log_1' => $dragons,
        ]);
    }

    function ultimateLeprechaun($dice, $diceBefore)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to transform ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'items_log' => $diceBefore,
            'items_log_1' => array(DRDungeonDice::getDie(DIE_CHEST)),
        ]);
    }

    function ultimateMercenary($monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to defeat ${items_log}');

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


    function ultimateOccultist($fighters, $skeletons)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to transform ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => array_merge($fighters, $skeletons),
            'items_log' => $skeletons,
            'items_log_1' => $fighters,
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

    function ultimateSorceress($monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to discard ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $monsters,
            'items_log' => $monsters
        ]);
    }

    function ultimateSpellSword($die)
    {

        $message = clienttranslate('${player_name} uses ${hero_name} as ${items_log}');


        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => array($die),
            'items_log' => array($die),
        ]);
    }

    function ultimateSzopin($dungeon_dice)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} and discards ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dungeon_dice,
            'items_log' => $dungeon_dice,
        ]);
    }

    function ultimateTracker($monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to discard ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $monsters,
            'items_log' => $monsters,
        ]);
    }

    function ultimateTristan($dice, $before)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to re-roll ${items_log} into ${items_log_1}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $dice,
            'items_log' => $before,
            'items_log_1' => $dice,
        ]);
    }

    function ultimateToulak($monsters)
    {
        $message = clienttranslate('${player_name} uses ${hero_name} to discard ${items_log}');

        $this->game->notifyAllPlayers(NOTIF_ITEM_MOVE, $message, [
            'player_name' => $this->game->getActivePlayerName(),
            'hero_name' => $this->game->components->getActivePlayerHero()->getName(),
            'items' => $monsters,
            'items_log' => $monsters,
        ]);
    }
}
