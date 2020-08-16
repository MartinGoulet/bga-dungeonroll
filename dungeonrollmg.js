/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * DungeonRollMg implementation : © Martin Goulet <martin.goulet@live.ca>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * dungeonrollmg.js
 *
 * DungeonRollMg user interface script
 * 
 */


define([
        "dojo",
        "dojo/_base/declare",
        "ebg/core/gamegui",
        "ebg/counter",
        "ebg/stock"
    ],
    function(dojo, declare) {
        return declare("bgagame.dungeonrollmg", ebg.core.gamegui, {

            constructor: function() {
                // Here, you can init the global variables of your user interface
                this.ItemType = {
                    Party: 1,
                    Dungeon: 2,
                    Token: 3,
                    HeroNovice: 4,
                    HeroMaster: 5
                }
            },

            /*
                setup:
                
                This method must set up the game user interface according to current game situation specified
                in parameters.
                
                The method is called each time the game interface is displayed to a player, ie:
                _ when the game starts
                _ when a player refreshes the game page (F5)
                
                "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
            */

            setup: function(gamedatas) {
                this.player_board = {};

                // Setting up player boards
                for (var player_id in gamedatas.players) {
                    var player = gamedatas.players[player_id];

                    // Setting up players boards if needed
                    var player_board_div = $('player_board_' + player_id);
                    dojo.place(this.format_block('jstpl_player_board', player), player_board_div);

                    this.player_board[player_id] = {
                        'hero': this.initStockHero('player_hero_' + player_id, false),
                        'delve': this.initCounter('player_delve_' + player_id, player.delve),
                        'inventory': this.initStockInventory('player_inventory_' + player_id)
                    }

                }

                Object.keys(gamedatas.heroes).forEach(player_id => {
                    this.addItemsToZone([gamedatas.heroes[player_id]], this.player_board[player_id].hero)
                    this.addItemsToZone(gamedatas.inventories[player_id], this.player_board[player_id].inventory)
                });

                this.items = {
                    'zone_party': this.initStockItemsGameDungeon("zone_party", 'onClickItem'),
                    'zone_dungeon': this.initStockItemsGameDungeon("zone_dungeon", 'onClickItem'),
                    'zone_play': this.initStockItemsGameDungeon("zone_play", 'onClickItem'),
                    'zone_graveyard': this.initStockItemsGameDungeon("zone_graveyard", 'onCreateItem'),
                    'zone_dragon_lair': this.initStockItemsGameDungeon("zone_dragon_lair"),
                    'zone_inventory': this.initStockItemsGameDungeon("zone_inventory", 'onClickItem'),
                    'zone_hero': this.initStockHero("zone_hero", true),
                    'zone_draft': this.initStockHero("draft_card", true, 'onSelectHero'),
                }

                // Dungeon level counter && Delve counter
                this.dungeon_level = this.initCounter('dungeon_level', this.gamedatas.level);
                this.delve_counter = this.initCounter('delve_counter', this.gamedatas.delve);

                // Add dice all over the board
                this.addItemsToBoard(gamedatas.items);

                // Setup game notifications to handle (see "setupNotifications" method below)
                this.setupNotifications();

                // Setup tooltip on heroes
                this.setupTooltipHeroes();

            },

            initCounter(div_name, initialValue) {
                var counter = new ebg.counter();
                counter.create(div_name);
                counter.setValue(initialValue);
                return counter;
            },

            getCardTooltip: function(card_type_id) {

                var card = this.gamedatas.card_types[card_type_id];

                var cardInfo = {
                    'ultimate': '<div class="keyword">' + _('Ultimate') + ' : </div>' + card.ultimate,
                    'specialty': '<div class="keyword">' + _('Specialty') + ' : </div>' + card.specialty,
                    'name': card.name
                };

                return this.format_block('jstpl_card_tooltip', cardInfo);
            },

            setupTooltipHeroes: function(card_div, card_type_id, card_id) {

                if (card_type_id != 0 && card_type_id !== undefined) {
                    var html = this.getCardTooltip(card_type_id);
                    this.addTooltipHtml(card_div.id, html, 100);
                }
            },

            initStockInventory: function(zone) {
                var stock = new ebg.stock();
                stock.create(this, $(zone), 25, 25);

                // stock.setSelectionAppearance("class");
                stock.setSelectionMode(0);
                stock.image_items_per_row = 6;
                stock.setOverlap(80, 0);

                // Load images
                Object.keys(this.gamedatas.items_treasure_tokens).forEach(key => {
                    var die = this.gamedatas.items_treasure_tokens[key];
                    stock.addItemType(key, die.weight, g_gamethemeurl + 'img/smalltreasures.png', die.image_index);
                });

                return stock;
            },

            initStockHero: function(zone, sizeNormal, callback) {
                var heroZone = new ebg.stock();
                if (sizeNormal == true) {
                    heroZone.create(this, $(zone), 124, 172);
                } else {
                    heroZone.create(this, $(zone), 50, 70);
                }

                heroZone.setSelectionAppearance("class");
                heroZone.setSelectionMode(0);
                heroZone.image_items_per_row = 8;

                Object.keys(this.gamedatas.card_types).forEach(card_type => {
                    var card = this.gamedatas.card_types[card_type];
                    if (sizeNormal == true) {
                        heroZone.addItemType(card_type, 1, g_gamethemeurl + 'img/cards.jpg', card.imageindex - 1);
                    } else {
                        heroZone.addItemType(card_type, 1, g_gamethemeurl + 'img/smallcards.jpg', card.imageindex - 1);
                    }
                });

                if (callback == undefined) {
                    heroZone.onItemCreate = dojo.hitch(this, 'setupTooltipHeroes');
                } else {
                    heroZone.onItemCreate = dojo.hitch(this, function(card_div, card_type_id, card_id) {
                        this.setupTooltipHeroes(card_div, card_type_id, card_id);
                        // Find the div
                        var div = dojo.query('#' + card_div.id);
                        // Associate the onclick method
                        div.connect('onclick', this, callback);
                    });
                }

                return heroZone;
            },

            initStockItemsGameDungeon: function(name, callback) {

                var stock = this.initStockItemsGame(name);
                if (callback !== undefined) {
                    stock.onItemCreate = dojo.hitch(this, function(card_div, card_type_id, card_id) {
                        this.setupDungeonDice(card_div, card_type_id, card_id, callback);
                    });
                }

                return stock;
            },

            initStockItemsGame: function(zone) {
                var stock = new ebg.stock();
                stock.create(this, $(zone), 42, 42);

                // stock.setSelectionAppearance("class");
                stock.setSelectionMode(0);
                stock.image_items_per_row = 6;

                // Load images
                this.loadItemsToStock(stock, this.gamedatas.items_party_dice);
                this.loadItemsToStock(stock, this.gamedatas.items_dungeon_dice);
                this.loadItemsToStock(stock, this.gamedatas.items_treasure_tokens);

                return stock;

            },

            loadItemsToStock: function(stock, items) {
                Object.keys(items).forEach(key => {
                    var die = items[key];
                    stock.addItemType(key, die.weight, g_gamethemeurl + die.image_file, die.image_index);
                });
            },

            setupDungeonDice: function(card_div, card_type_id, card_id, callback) {

                // Find the div
                var div = dojo.query('#' + card_div.id);
                // Associate the onclick method
                div.connect('onclick', this, callback);
                // If the dice is empty (placeholder)
                if (card_type_id == 0) {
                    // Add class empty to create a border
                    div.addClass("empty");
                }

                var info = card_id.split("_")
                var idItem = info[info.length - 1];

                if (idItem >= 1000 && idItem < 2000) {
                    // Add class empty to create a border
                    div.addClass("temporary-item");
                } else if (idItem >= 2000 && idItem <= 3000) {
                    div.addClass("temporary-ability");
                }
            },

            addItemsToBoard(itemsToAdd) {

                Object.keys(this.items).forEach(key => {
                    // Add dice to the specific zone
                    var zone = key.replace("zone_", "");
                    var items = itemsToAdd.filter(x => x.zone == zone);
                    this.addItemsToZone(items, this.items[key]);
                });

            },

            addItemsToZone: function(items, zone) {
                // Clear the zone
                zone.removeAll();
                // Add dice
                for (var i in items) {
                    var item = items[i];
                    zone.addToStockWithId(item.type + "_" + item.value, item.id);
                }
            },

            ///////////////////////////////////////////////////
            //// Game & client states

            // onEnteringState: this method is called each time we are entering into a new game state.
            //                  You can use this method to perform some user interface changes at this moment.
            //
            onEnteringState: function(stateName, args) {

                switch (stateName) {

                    case 'draftHeroes':
                        dojo.style('draft_zone', 'display', 'block');
                        dojo.style('board', 'display', 'none');
                        this.items['zone_draft'].removeAll();
                        args.args.heroes.forEach(hero => {
                            var type = hero['type'] + '_' + hero['value'];
                            this.items['zone_draft'].addToStockWithId(type, hero['id']);
                        });
                        break;

                    case 'initPlayerTurn':
                        dojo.style('draft_zone', 'display', 'none');
                        dojo.style('board', 'display', 'block');

                    case 'dummmy':
                        break;
                }
            },

            // onLeavingState: this method is called each time we are leaving a game state.
            //                 You can use this method to perform some user interface changes at this moment.
            //
            onLeavingState: function(stateName) {
                switch (stateName) {
                    case 'dummmy':
                        break;
                }
            },

            // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
            //                        action status bar (ie: the HTML links in the status bar).
            //        
            onUpdateActionButtons: function(stateName, args) {

                this.removeActionButtons();
                dojo.query("li.selected").removeClass("selected");
                dojo.query("#nav_" + stateName).addClass("selected");

                if (this.isCurrentPlayerActive()) {
                    switch (stateName) {
                        case 'postFormingParty':
                        case 'monsterPhase':
                        case 'lootPhase':
                        case 'regroupPhase':
                        case 'dragonPhase':
                            this.showCommands(args.commands);
                            break;

                        case 'quaffPotion':
                            dojo.query("#nav_" + args.currentPhase).addClass("selected");
                            this.showDiceButton();
                    }
                }
            },

            showDiceButton: function() {
                this.removeActionButtons();

                Object.keys(this.gamedatas.items_party_dice).forEach(key => {
                    var die = this.gamedatas.items_party_dice[key];
                    this.addActionButton('dice_' + key, '<div class="sicon ' + die.small_icon + '"></div>', 'chooseDieGain', false, null, 'gray');
                });

            },

            showCommands: function(commands) {

                this.removeActionButtons();

                if (commands === undefined) return;

                commands.forEach(command => {
                    if (command.commands === undefined) {
                        if (command.button_color) {
                            this.addActionButton('cmd_' + command.id, command.text, 'executeCommand', null, false, command.button_color);
                        } else {
                            this.addActionButton('cmd_' + command.id, command.text, 'executeCommand');
                        }
                    } else {
                        Object.keys(command.commands).forEach(key => {
                            this.addActionButton('cmd_' + command.id + '_' + key, command.commands[key], 'executeCommand');
                        });
                    }
                });
            },



            onSelectHero: function(evt) {
                // Stop this event propagation
                evt.preventDefault();
                dojo.stopEvent(evt);
                if (this.checkAction('selectHero')) {
                    var hero_id = evt.currentTarget.id.substring(evt.currentTarget.id.lastIndexOf("_") + 1);
                    this.ajaxcall("/dungeonrollmg/dungeonrollmg/selectHero.html", {
                        hero_id: hero_id,
                        lock: true
                    }, this, function(result) {});
                }
            },

            executeCommand: function(evt) {
                // Stop this event propagation
                evt.preventDefault();
                dojo.stopEvent(evt);


                if (this.checkAction('executeCommand')) {

                    var commandId = evt.currentTarget.id.substr(4);
                    var info = commandId.split("_");
                    var command = this.gamedatas.command_infos[info[0]];
                    var subCommand = 0;
                    if (info.length > 1) {
                        subCommand = info[1];
                    }

                    if (command.confirmation !== undefined) {

                        this.confirmationDialog(
                            command.confirmation,
                            dojo.hitch(this, function() {
                                this.ajaxcall("/dungeonrollmg/dungeonrollmg/executeCommand.html", {
                                    id: command.id,
                                    sub: subCommand,
                                    lock: true
                                }, this, function(result) {});
                            }));

                    } else {
                        this.ajaxcall("/dungeonrollmg/dungeonrollmg/executeCommand.html", {
                            id: command.id,
                            sub: subCommand,
                            lock: true
                        }, this, function(result) {});
                    }
                }
            },

            chooseDieGain: function(evt) {
                if (this.checkAction('chooseDieGain')) {
                    var diceType = evt.currentTarget.id.substr(5);
                    this.ajaxcall("/dungeonrollmg/dungeonrollmg/chooseDieGain.html", {
                        type: diceType.split("_")[0],
                        value: diceType.split("_")[1],
                        lock: true
                    }, this, function(result) {});
                }
            },

            ///////////////////////////////////////////////////
            //// Utility methods

            /*
             
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
             
            */

            ///////////////////////////////////////////////////
            //// Player's action

            onCreateItem: function(evt) {
                // Stop this event propagation
                evt.preventDefault();
                dojo.stopEvent(evt);
            },

            onClickItem: function(evt) {
                // Stop this event propagation
                evt.preventDefault();
                dojo.stopEvent(evt);
                if (this.isCurrentPlayerActive()) {
                    var die_id = evt.currentTarget.id.substring(evt.currentTarget.id.lastIndexOf("_") + 1);
                    this.moveDice(die_id);
                }
            },

            moveDice: function(die_id) {
                this.removeActionButtons();
                if (this.checkAction('moveItem')) {
                    this.ajaxcall("/dungeonrollmg/dungeonrollmg/moveItem.html", {
                        die_id: die_id,
                        lock: true
                    }, this, function(result) {});
                }
            },

            ///////////////////////////////////////////////////
            //// Reaction to cometD notifications

            /*
            setupNotifications:
             
            In this method, you associate each of your game notifications with your local method to handle it.
             
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
            your dungeonrollmg.game.php file.
             
            */
            setupNotifications: function() {

                dojo.subscribe('updateScores', this, "notif_updateScores");
                dojo.subscribe('updatePossibleActions', this, "notif_updatePossibleActions");
                dojo.subscribe('onItemsMoved', this, "notif_onItemsMoved");
                dojo.subscribe('onDiceRolled', this, "notif_onItemsMoved");
                dojo.subscribe('onNewPlayerTurn', this, "notif_onNewPlayerTurn");
                dojo.subscribe('onNewLevel', this, "notif_onNewLevel");
                dojo.subscribe('onNewTokens', this, "notif_onNewTokens");
                dojo.subscribe('onNewDelve', this, "notif_onNewDelve");
                dojo.subscribe('onHeroLevelUp', this, "notif_onHeroLevelUp");
                dojo.subscribe('onSelectHero', this, "notif_onSelectHero");

                this.notifqueue.setSynchronous('onDiceRolled', 1250);

            },

            notif_updateScores: function(notif) {
                for (var player_id in notif.args.scores) {
                    this.scoreCtrl[player_id].toValue(notif.args.scores[player_id]);
                }
            },

            notif_onItemsMoved: function(notif) {
                var board = this.player_board[this.getActivePlayerId()];

                notif.args.items.forEach(item => {
                    var item_type = item.type + "_" + item.value;

                    if (item.previous_zone == 'box' || this.isStringEmpty(item.previous_zone)) {
                        this.items['zone_' + item.zone].addToStockWithId(item_type, item.id, $("zone_graveyard"));
                        if (item.type == this.ItemType.Token && board) {
                            board.inventory.addToStockWithId(item_type, item.id);
                        }
                    } else if (item.zone == 'box' || this.isStringEmpty(item.zone)) {
                        var from = $(this.items['zone_' + item.previous_zone].getItemDivId(item.id));
                        this.slideToObjectAndDestroy(from, $("zone_graveyard"), 1000, 0);
                        this.items['zone_' + item.previous_zone].removeFromStockById(item.id);
                        if (item.type == this.ItemType.Token && board) {
                            board.inventory.removeFromStockById(item.id);
                        }
                    } else if (item.previous_zone == item.zone) {
                        this.items['zone_' + item.zone].removeFromStockById(item.id);
                        this.items['zone_' + item.zone].addToStockWithId(item_type, item.id);
                    } else {
                        var from = $(this.items['zone_' + item.previous_zone].getItemDivId(item.id));
                        this.items['zone_' + item.zone].addToStockWithId(item_type, item.id, from);
                        this.items['zone_' + item.previous_zone].removeFromStockById(item.id);
                    }

                });
            },

            notif_onHeroLevelUp: function(notif) {
                var heroZone = this.player_board[notif.args.player_id].hero;
                var from = $(heroZone.getItemDivId(notif.args.hero_novice['id']));
                heroZone.addToStockWithId("5_" + notif.args.hero_master['value'], notif.args.hero_master['id'], from);
                heroZone.removeFromStockById(notif.args.hero_novice['id']);
            },

            notif_updatePossibleActions: function(notif) {
                this.showCommands(notif.args.commands);
            },

            notif_onRollDice: function(notif) {
                notif.args.dice.forEach(die => {
                    var die_type = die.type + "_" + die.value;
                    this.items['zone_' + die.zone].removeFromStockById(die.id);
                    this.items['zone_' + die.zone].addToStockWithId(die_type, die.id);
                });
            },

            notif_onNewPlayerTurn: function(notif) {
                this.addItemsToBoard([]);
            },

            notif_onNewLevel: function(notif) {
                this.dungeon_level.setValue(notif.args.level);
            },

            notif_onNewDelve: function(notif) {
                this.delve_counter.setValue(notif.args.delve_number);
                this.player_board[notif.args.player_id].delve.setValue(notif.args.delve_number);
            },

            notif_onNewTokens: function(notif) {
                var playerInventory = this.player_board[this.getActivePlayerId()].inventory;

                notif.args.tokens.forEach(token => {
                    var from = $(this.items['zone_' + token.previous_zone].getItemDivId(token.from));
                    playerInventory.addToStockWithId(token.type + "_" + token.value, token.id, from);
                    this.items['zone_' + token.zone].addToStockWithId(token.type + "_" + token.value, token.id, from);
                    this.items['zone_' + token.previous_zone].removeFromStockById(token.from);
                });
            },

            notif_onSelectHero: function(notif) {
                var hero = notif.args.hero;
                var from = $(this.items['zone_' + hero.previous_zone].getItemDivId(hero.id));
                this.player_board[hero['owner']].hero.addToStockWithId(hero.type + "_" + hero.value, hero.id, from);
                this.items['zone_' + hero.previous_zone].removeFromStockById(hero.from);
            },

            isStringEmpty: function(value) {
                return value === undefined || value === null || value === '';
            }

        });
    });