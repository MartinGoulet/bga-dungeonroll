<?php

/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * DungeonRoll implementation : © Martin Goulet <martin.goulet@live.ca>
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 * 
 * dungeonroll.game.php
 *
 * This is the main file for your game logic.
 *
 * In this PHP file, you are going to defines the rules of the game.
 *
 */


require_once(APP_GAMEMODULE_PATH . 'module/table/table.game.php');

require_once('modules/constants.inc.php');

require_once('modules/DRGlobalVariable.php');
require_once('modules/DRCommandManager.php');
require_once('modules/DRComponentManager.php');
require_once('modules/DRHeroesManager.php');
require_once('modules/DRItemManager.php');
require_once('modules/DRNotification.php');
require_once('modules/DRStatistic.php');


require_once('modules/DRUtils.php');
require_once('modules/DRItem.php');
require_once('modules/DRDungeonDice.php');
require_once('modules/DRPartyDice.php');
require_once('modules/DRTreasureToken.php');



class DungeonRoll extends Table
{
    function __construct()
    {
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
        parent::__construct();

        self::initGameStateLabels(array(
            // Global variables
            GL_CURRENT_TURN => GL_CURRENT_TURN_ID,
            GL_CURRENT_LEVEL => GL_CURRENT_LEVEL_ID,
            GL_MAX_TURN => GL_MAX_TURN_ID,
            GL_HERO_ACTIVATED => GL_HERO_ACTIVATED_ID,
            GL_CHOOSE_DIE_COUNT => GL_CHOOSE_DIE_COUNT_ID,
            GL_CHOOSE_DIE_STATE => GL_CHOOSE_DIE_STATE_ID,
            // Game variants
            GV_GAME_OPTION => GV_GAME_OPTION_ID
        ));

        // Initialize helper class
        $this->stats      = new DRStatistic($this);
        $this->vars       = new DRGlobalVariable($this);
        $this->manager    = new DRItemManager($this);
        $this->notif      = new DRNotification($this, $this->vars);
        $this->components = new DRComponentManager($this);
        $this->commands   = new DRCommandManager($this);
    }

    protected function getGameName()
    {
        // Used for translations and stuff. Please do not modify.
        return "dungeonroll";
    }

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame($players, $options = array())
    {
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];

        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach ($players as $player_id => $player) {
            $color = array_shift($default_colors);
            $values[] = "('" . $player_id . "','$color','" . $player['player_canal'] . "','" . addslashes($player['player_name']) . "','" . addslashes($player['player_avatar']) . "')";
        }
        $sql .= implode($values, ',');
        self::DbQuery($sql);
        self::reattributeColorsBasedOnPreferences($players, $gameinfos['player_colors']);
        self::reloadPlayersBasicInfos();

        // Init global values with their initial values
        $this->vars->initVariables($players);

        // Init game statistics
        $this->stats->initStats($players);

        // Create all components in the database
        $this->manager->createGameComponents($this->items, $this->card_types);

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $players = self::getCollectionFromDb("SELECT player_id id, player_score score, player_delve delve FROM player ");

        return [
            'players' => $players,
            'heroes' => $this->components->getHeroesByPlayer(),
            'items' => $this->components->getActivePlayerItems(),
            'level' => $this->vars->getDungeonLevel(),
            'delve' => $this->getCurrentDelve(),
            'currentTurn' => $this->vars->getCurrentTurn(),

            'card_types' => $this->card_types,
            'items_party_dice' => $this->items_party_dice,
            'items_dungeon_dice' => $this->items_dungeon_dice,
            'items_treasure_tokens' => $this->items_treasure_tokens,

            'command_infos' => $this->getCommandInfos(),
            'hero' => $this->components->getActivePlayerHero()->getUIData(),

            'inventories' => $this->manager->getInventoryByPlayer($players),
        ];
    }

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        $maxTurn = (self::getPlayersNumber() * 3);
        $currentTurn = $this->vars->getCurrentTurn() - 1;
        $level = $this->vars->getDungeonLevel();

        // Progression in the delve
        $levelProgression = (1 / $maxTurn) * ($level / 10);
        // Number of delve completed
        $turnProgession = ($currentTurn / $maxTurn);
        // All progession of previous delve + pourcentage of the current delve
        return ($turnProgession + $levelProgression) * 100;
    }


    //////////////////////////////////////////////////////////////////////////////
    //////////// Utility functions
    ////////////  

    function getCommandInfos()
    {
        foreach ($this->command_infos as $idCmd => &$cmd) {
            $cmd['id'] = $idCmd;
        }
        return $this->command_infos;
    }

    function getCurrentDelve()
    {
        // The current delve is the current turn \ 3 (0 - 2)
        $currentDelve = intdiv($this->vars->getCurrentTurn() - 1, self::getPlayersNumber());
        return $currentDelve + 1;
    }
    function incPlayerDelve()
    {
        $player_id = $this->getActivePlayerId();
        self::DbQuery("UPDATE player SET player_delve = player_delve + 1 WHERE player_id='$player_id'");
    }

    function incPlayerScore($nbr)
    {
        $player_id = $this->getActivePlayerId();
        // Get score before update
        $score = $this->getUniqueValueFromDB("SELECT player_score FROM player WHERE player_id='$player_id'");
        // Set score
        self::DbQuery("UPDATE player SET player_score = player_score + $nbr WHERE player_id='$player_id'");
        // Check if hero level up        
        $hero = $this->components->getActivePlayerHero();

        if ($score < 5 && ($score + $nbr) >= 5 && $hero->canLevelUp()) {
            $this->heroLevelUp();
        }
    }

    function heroLevelUp()
    {
        $heroNovice = $this->components->getActivePlayerItemsByZone(ZONE_HERO);
        $heroMaster = $this->components->getItemsByTypeAndValue(TYPE_MASTER_HERO, $heroNovice[0]['value']);

        $heroNovice = DRItem::setZone($heroNovice, ZONE_BOX);
        $heroNovice = DRItem::setOwner($heroNovice, null);

        $heroMaster = DRItem::setZone($heroMaster, ZONE_HERO);
        $heroMaster = DRItem::setOwner($heroMaster, $this->getActivePlayerId());

        $heroMaster[0]['from'] = $heroNovice[0]['id'];
        $heroMaster[0]['previous_zone'] = ZONE_BOX;

        $heroes = array_merge($heroNovice, $heroMaster);

        $this->manager->updateItems($heroes);
        $this->NTA_itemMove($heroes);
        $this->notif->heroLevelUp($heroNovice[0], $heroMaster[0]);
    }

    function transformMonstersToDragons($itemsInPlay)
    {

        $monsters = DRDungeonDice::getMonsterDices($itemsInPlay);

        foreach ($monsters as &$monster) {
            $monster['value'] = DIE_DRAGON;
        }

        $monsters = DRItem::setZone(array_values($monsters), ZONE_DRAGON_LAIR);

        return $monsters;
    }

    static function getZoneAfterClickDice($dice, $state)
    {
        if ($dice['zone'] == ZONE_PLAY) {
            if (DRItem::isPartyDie($dice)) {
                return ZONE_PARTY;
            } else if (DRItem::isTreasureToken($dice)) {
                return ZONE_INVENTORY;
            } else {
                return ZONE_DUNGEON;
            }
        } else {
            // Go to the play zone
            return ZONE_PLAY;
        }
    }

    //////////////////////////////////////////////////////////////////////////////
    //////////// Player actions
    //////////// 

    function executeCommand($command_id, $sub_command_id)
    {
        $command = $this->commands->getCommand($command_id);

        DRUtils::userSystemTrue(
            self::_('Command not found'),
            $command != null
        );

        DRUtils::userSystemTrue(
            sprintf(self::_("You can't use this command yet : %s"), $command->getCommandInfo()['name']),
            $command->canExecute()
        );

        $command->execute($sub_command_id);
    }

    function selectHero($hero_id)
    {
        // Get the die
        $hero = $this->components->getItemById($hero_id);
        $hero['zone'] = ZONE_DRAFT;

        $updateItems = DRItem::setOwner(array($hero), $this->getActivePlayerId());
        $updateItems = DRItem::setZone($updateItems, ZONE_HERO);

        // Update the change
        $this->manager->updateItems($updateItems);
        // Notify all players for the move
        $this->notif->selectHero($updateItems[0]);

        $this->gamestate->nextState();
    }

    function stNextDraftHero()
    {
        // Activate next player
        $player_id = self::activeNextPlayer();
        // Give more time to the player
        self::giveExtraTime($player_id);

        $currentPlayerHero = $this->components->getItemsByPlayer($player_id);

        if (sizeof($currentPlayerHero) > 0) {
            $this->gamestate->nextState('end');
        } else {
            $this->gamestate->nextState('next');
        }
    }

    function moveItem($die_id)
    {
        // Get the die
        $die = $this->components->getItemById($die_id);

        // It's impossible to move the dragon die
        DRUtils::userAssertTrue(
            self::_("You can't move the dragon"),
            !DRDungeonDice::isDragon($die)
        );

        // Check where the die can go
        $location = self::getZoneAfterClickDice($die, $this->gamestate->state()['name']);
        // Change the location of the die
        $dice = DRItem::setZone(array($die), $location);
        // Update the change
        $this->manager->updateItems($dice);
        // Notify all players for the move
        $this->NTA_itemMove($dice);
        // Notify the player for the all possible move
        $this->notif->updatePossibleActions();
    }

    function chooseDieGain($type, $value)
    {
        $diceOfAskedType = array_values(array_filter($this->components->getActivePlayerItemsByZone(ZONE_GRAVEYARD), function ($die) use ($type) {
            return $die['type'] == $type;
        }));

        if (sizeof($diceOfAskedType) == 0) {
            throw new BgaUserException(self::_('No more dice to re-roll'));
        }
        // Select the first die in the graveyard
        $rerollDice = $diceOfAskedType[0];
        $rerollDice['value'] = $value;

        // Set new zone for both dice
        $updatedDice = DRItem::setZone(array($rerollDice), ZONE_PARTY);

        // Update database and notify players
        $this->manager->updateItems($updatedDice);
        $this->NTA_itemMove($updatedDice);

        $count = $this->vars->decChooseDieCount();

        if ($count > 0) {
            $this->gamestate->nextState('next');
        } else {
            $previous_state = $this->vars->getChooseDieState();
            $this->gamestate->nextState($previous_state);
        }
    }

    // TODO To remove before production
    // function debug()
    // {

    //     //$this->vars->setIsHeroActivated(false);

    //     $monsters = $this->components->getActivePlayerItemsByZone(ZONE_DUNGEON);
    //     // $monsters = DRUtils::filter($monsters, 'DRDungeonDice::isSkeleton');

    //     foreach ($monsters as &$monster) {
    //         $monster['value'] = DIE_CHEST;
    //     }
    //     // $monsters = DRItem::setZone($monsters, ZONE_DUNGEON);

    //     $this->manager->updateItems($monsters);
    //     $this->NTA_itemMove($monsters);
    // }


    //////////////////////////////////////////////////////////////////////////////
    //////////// Game state arguments
    ////////////

    function argDraftHeroes()
    {
        return array(
            'heroes' => $this->components->getNoviceHeroes(),
        );
    }

    function argGenericPhasePlayerTurn()
    {
        return array(
            'commands' => $this->commands->getActiveCommands(),
        );
    }

    function argQuaffPotion()
    {
        return array(
            'currentPhase' => $this->vars->getChooseDieState(),
            'nbr' => $this->vars->getChooseDieCount()
        );
    }

    //////////////////////////////////////////////////////////////////////////////
    //////////// Game state actions
    ////////////

    function stGameOption()
    {
        $mode = $this->vars->getGameOption();

        if ($mode == GAME_OPTION_RANDOM_HERO) {
            $this->gamestate->nextState("random");
        } else if ($mode == GAME_OPTION_SELECT_HERO) {
            $this->gamestate->nextState("select");
        } else if ($mode == GAME_OPTION_NO_HERO) {
            $this->gamestate->nextState("nohero");
        }
    }

    function stRandomHero()
    {
        $players = $this->loadPlayersBasicInfos();
        $noviceHeroes = $this->components->getItemsByType(TYPE_NOVICE_HERO);

        $selectedHeroes = array();
        foreach ($players as $player_id => $dummy) {
            // Get a random index in the array
            $index = bga_rand(0, sizeof($noviceHeroes) - 1);
            // Retrieve hero
            $hero = $noviceHeroes[$index];
            // Remove hero from the array (for the next player)
            unset($noviceHeroes[$index]);
            $noviceHeroes = array_values($noviceHeroes);

            $hero['owner'] = $player_id;
            $hero['zone'] = ZONE_HERO;
            $selectedHeroes[] = $hero;
        }

        // Update selected hero in the database
        $this->manager->updateItems($selectedHeroes);

        // Next state
        $this->gamestate->nextState();
    }

    function stInitPlayerTurn()
    {
        // Reset the board
        $this->notif->newPlayerTurn();

        // The player is at the start of the dungeon
        $this->vars->incCurrentTurn();
        $this->incPlayerDelve();

        // Reset the dungeon level
        $this->vars->setDungeonLevel(0);

        // Notify for the new delve
        $this->notif->newDelve();

        // Erase all info on dice
        $this->manager->resetDice();

        // Allow the player to use his hero ultima abililty
        $this->vars->setIsHeroActivated(false);

        // Next state
        $this->gamestate->nextState();
    }


    function stFormingParty()
    {
        // Move treasures and hero to the inventory zone
        $treasures = $this->components->getActivePlayerItemsByZone(ZONE_INVENTORY);
        $heroes = $this->components->getActivePlayerItemsByZone(ZONE_HERO);
        $this->NTA_itemMove(array_merge($treasures, $heroes));

        // Roll all party dice
        $party_dices = $this->components->getItemsByType(TYPE_PARTY_DIE);
        $rolledDice = DRItem::rollDice($party_dices);
        $rolledDice = DRItem::setZone($rolledDice, ZONE_PARTY);

        $this->notif->rollPartyDice($rolledDice);

        $hero = $this->components->getActivePlayerHero();
        $hero->stateAfterFormingParty($rolledDice);

        $this->manager->updateItems($rolledDice);

        if ($hero instanceof DRMercenary) {
            // Next state
            $this->gamestate->nextState('mercenary');
        } else {
            // Next state
            $this->gamestate->nextState('dungeon');
        }
    }

    function stRollDungeonDice()
    {
        // The player moves one level further into the dungeon
        $level = $this->vars->incDungeonLevel();
        $this->notif->newDungeonLevel($level);

        // Get all available dungeon dice to roll
        $dungeonDiceAvailable = $this->components->getItemsByTypeAndZone(TYPE_DUNGEON_DIE, ZONE_BOX);
        // Select a number of dungeon dice equal to the maximum of the level
        $dungeonDiceToRoll = array_slice($dungeonDiceAvailable, 0, min(sizeof($dungeonDiceAvailable), $level));
        // Roll dice
        $rolledDice = DRItem::rollDice($dungeonDiceToRoll);

        $dragonsDice = DRDungeonDice::getDragonDice($rolledDice);
        $rolledDragonDice = DRItem::setZone($dragonsDice, ZONE_DRAGON_LAIR);
        $this->manager->updateItems($rolledDragonDice);

        $dungeonDice = DRDungeonDice::getDungeonDiceWithoutDragon($rolledDice);
        $rolledDungeonDice = DRItem::setZone($dungeonDice, ZONE_DUNGEON);
        $this->manager->updateItems($rolledDungeonDice);

        // Notify for the move
        $this->NTA_itemMove(array_merge($rolledDragonDice, $rolledDungeonDice));

        // Next state
        $this->gamestate->nextState();
    }

    function stPreLootPhase()
    {
        $dice = $this->components->getActivePlayerUsableItems();
        $dungeon = DRDungeonDice::getDungeonDiceWithoutDragon($dice);

        if (sizeof($dungeon) == 0) {
            $this->notif->skipLootPhase();
            $this->gamestate->nextState('preDragonPhase');
        } else {
            $this->gamestate->nextState('lootPhase');
        }
    }

    function stPreDragonPhase()
    {
        // Get all dragons dice in play
        $dice = $this->components->getActivePlayerUsableItems();
        $dragons = DRDungeonDice::getDragonDice($dice);

        // If 3 or more dragons is found, the player must fight them
        if (sizeof($dragons) >= 3) {
            $dragons = DRItem::setZone($dragons, ZONE_PLAY);
            $this->manager->updateItems($dragons);
            $this->NTA_itemMove($dragons);

            // Next state
            $this->gamestate->nextState("dragonPhase");
        } else {
            $this->notif->skipDragonPhase();
            // Next state
            $this->gamestate->nextState('regroupPhase');
        }
    }

    function stPreRegroupPhase()
    {
        $items = $this->components->getActivePlayerItems();
        $itemsTemporary = DRUtils::filter($items, 'DRItem::isTemporaryItem');
        $itemsTemporary = DRItem::setZone($itemsTemporary, ZONE_BOX);
        $itemsTemporary = DRItem::setOwner($itemsTemporary, null);

        if (sizeof($itemsTemporary) >= 1) {
            $this->components->updateItems($itemsTemporary);
            $this->NTA_itemMove($itemsTemporary);
        }

        $this->manager->deleteTemporaryItem();

        // Next state
        $this->gamestate->nextState();
    }

    function stNextPlayer()
    {

        $items = $this->components->getActivePlayerUsableItems();
        $temporaryAbilities = DRUtils::filter($items, 'DRItem::isTemporaryAbility');
        $temporaryAbilities = DRItem::setZone($temporaryAbilities, ZONE_BOX);

        if (sizeof($temporaryAbilities) >= 1) {
            $this->components->updateItems($temporaryAbilities);
            $this->NTA_itemMove($temporaryAbilities);
        }

        $this->manager->deleteTemporaryAbility();

        // Move tokens in Playing Area into the right area
        $itemsInPlay = $this->components->getActivePlayerItemsByZone(ZONE_PLAY);
        $tokens = DRTreasureToken::getTreasureTokens($itemsInPlay);

        if (sizeof($tokens) > 0) {
            $tokens = DRItem::setZone($tokens, ZONE_INVENTORY);
            $this->manager->updateItems($tokens);
        }

        // Increment the number of turn complete by the active player
        if ($this->vars->getCurrentTurn() < $this->vars->getMaxTurn()) {
            // Activate next player
            $player_id = self::activeNextPlayer();
            // Give more time to the player
            self::giveExtraTime($player_id);
            // Next delve
            $this->gamestate->nextState('formingParty');
        } else {
            $this->gamestate->nextState('endGame');
        }
    }

    function stGameEndScoring()
    {
        $sql = 'SELECT player_id, player_score FROM player';
        $finalSituation = self::getCollectionFromDB($sql);

        // Get all treasures tokens from all players
        $sql = "SELECT item_id id, item_value value, item_type type, item_zone zone, owner_id 
                  FROM item  WHERE owner_id is not null AND item_type = 3 AND item_zone != 'box';";

        $treasures = self::getObjectListFromDB($sql);

        foreach ($finalSituation as $player_id => $player) {

            // Set nbr of xp from treasures
            $playerTreasures = array_values(array_filter($treasures, function ($token) use ($player_id) {
                return $token['owner_id'] == $player_id;
            }));
            $nbrXpTreasures = sizeof($playerTreasures);
            self::setStat($nbrXpTreasures, STAT_XP_TREASURE, $player_id);

            // Set 1 xp for each townPortal
            $townPortal = DRItem::getSameAs($playerTreasures, DRTreasureToken::getToken(TOKEN_TOWN_PORTAL));
            $nbrXpTownPortal = sizeof($townPortal);
            self::setStat($nbrXpTownPortal, STAT_XP_TOWN_PORTAL, $player_id);

            // Set 2 xp for each pair of dragon scale
            $dragonScales = DRItem::getSameAs($playerTreasures, DRTreasureToken::getToken(TOKEN_DRAGON_SCALES));
            $nbrXpDragonScales = intdiv(sizeof($dragonScales), 2) * 2;
            self::setStat($nbrXpDragonScales, STAT_XP_DRAGON_SCALE, $player_id);

            // The player with the fewest number of treasures win; (36 treasure tokens max);
            $tieBreaker = 36 - sizeof($playerTreasures);

            self::DbQuery("UPDATE player 
                              SET player_score = player_score + $nbrXpTreasures + $nbrXpTownPortal + $nbrXpDragonScales,
                                  player_score_aux = $tieBreaker
                            WHERE player_id='$player_id' ");
        }

        $this->notif->updatedScores();

        $this->gamestate->nextState('');
    }



    //////////////////////////////////////////////////////////////////////////////
    //////////// Zombie
    ////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn($state, $active_player)
    {
        $statename = $state['name'];

        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                case 'draftHeroes':
                    // Select first hero available
                    $hero = $this->components->getNoviceHeroes()[0];
                    $this->selectHero($hero['id']);
                    break;
                case 'postFormingParty':
                    $command = $this->commands->getCommandByName('endFormingPartyPhase');
                    $command->execute(0);
                    break;
                case 'monsterPhase':
                case 'dragonPhase':
                    $command = $this->commands->getCommandByName('fleeDungeon');
                    $command->execute(0);
                    break;
                case 'lootPhase':
                    $command = $this->commands->getCommandByName('endLootPhase');
                    $command->execute(0);
                    break;
                case 'regroupPhase':
                    $command = $this->commands->getCommandByName('retireTavern');
                    $command->execute(0);
                    break;
                case 'quaffPotion':
                    $this->chooseDieGain(TYPE_PARTY_DIE, DIE_CHAMPION);
                    break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive($active_player, '');

            return;
        }

        throw new feException("Zombie mode not supported at this game state: " . $statename);
    }

    ///////////////////////////////////////////////////////////////////////////////////:
    ////////// Notifications
    //////////


    function NTA_itemMove($items, $message = '')
    {
        // Notify the discard of the scroll
        self::notifyAllPlayers("onItemsMoved", $message, array(
            'items' => $items,
            'player_name' => $this->getActivePlayerName()
        ));
    }


    ///////////////////////////////////////////////////////////////////////////////////:
    ////////// DB upgrade
    //////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */

    function upgradeTableDb($from_version)
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345

        // Example:
        //        if( $from_version <= 1404301345 )
        //        {
        //            // ! important ! Use DBPREFIX_<table_name> for all tables
        //
        //            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
        //            self::applyDbUpgradeToAllDB( $sql );
        //        }
        //        if( $from_version <= 1405061421 )
        //        {
        //            // ! important ! Use DBPREFIX_<table_name> for all tables
        //
        //            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
        //            self::applyDbUpgradeToAllDB( $sql );
        //        }
        //        // Please add your future database scheme changes here
        //
        //


    }
}