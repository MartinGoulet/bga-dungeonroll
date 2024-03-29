/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * DungeonRoll implementation : © Martin Goulet <martin.goulet@live.ca>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * dungeonroll.js
 *
 * DungeonRoll user interface script
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
        return declare("bgagame.dungeonroll", ebg.core.gamegui, {

            constructor: function() {
                // Here, you can init the global variables of your user interface
                this.ItemType = {
                    Party: 1,
                    Dungeon: 2,
                    Token: 3,
                    HeroNovice: 4,
                    HeroMaster: 5
                };

                this.PartyType = {
                    Scroll: "1_1",
                    Mage: "1_2",
                    Cleric: "1_3",
                    Fighter: "1_4",
                    Thief: "1_5",
                    Champion: "1_6",
                    Generic_Dragon: "1_7",
                }

                this.DungeonType = {
                    Dragon: "2_1",
                    Potion: "2_2",
                    Ooze: "2_3",
                    Skeleton: "2_4",
                    Chest: "2_5",
                    Goblin: "2_6",
                };

                this.InventoryType = {
                    VorpalSword: "3_1",
                    Talisman: "3_2",
                    ScepterOfPower: "3_3",
                    Thieves: "3_4",
                    Scroll: "3_5",
                    RingInvisibility: "3_6",
                    DragonScales: "3_7",
                    Elixir: "3_8",
                    DragonBait: "3_9",
                    TownPortal: "3_10",
                };

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

                /* Old images (must find a way to remove them) */
                this.dontPreloadImage('voiddice.png');
                this.dontPreloadImage('level_tracker.jpg');
                this.dontPreloadImage('cards.jpg');
                this.dontPreloadImage('smallcards.jpg');
                this.dontPreloadImage('smalltreasures.png');

                this.player_board = {};

                // Setting up player boards
                for (var player_id in gamedatas.players) {
                    var player = gamedatas.players[player_id];

                    // Setting up players boards if needed
                    var player_board_div = $('player_board_' + player_id);
                    var html = this.format_block('jstpl_player_board', {
                        id: player.id,
                        delve: _('Delve')
                    })
                    dojo.place(html, player_board_div);

                    this.player_board[player_id] = {
                        'hero': this.initStockHero('player_hero_' + player_id, false),
                        'delve': this.initCounter('player_delve_' + player_id, player.delve),
                        'inventory': this.initStockInventory('player_inventory_' + player_id)
                    }

                    if (gamedatas.useHero === false) {
                        dojo.style('player_hero_' + player_id, 'display', 'none');
                    }
                }

                Object.keys(gamedatas.heroes).forEach(player_id => {
                    this.addItemsToZone([gamedatas.heroes[player_id]], this.player_board[player_id].hero)
                });

                Object.keys(gamedatas.inventories).forEach(player_id => {
                    this.addItemsToZone(gamedatas.inventories[player_id], this.player_board[player_id].inventory)
                });

                this.items = {
                    'zone_party': this.initStockItemsGameDungeon("zone_party", 'onClickItem'),
                    'zone_dungeon': this.initStockItemsGameDungeon("zone_dungeon", 'onClickItem'),
                    'zone_play': this.initStockItemsGameDungeon("zone_play", 'onClickItem'),
                    'zone_graveyard': this.initStockItemsGameDungeon("zone_graveyard", 'onCreateItem'),
                    'zone_dragon_lair': this.initStockItemsGameDungeon("zone_dragon_lair", 'onClickItem'),
                    'zone_inventory': this.initStockItemsGameDungeon("zone_inventory", 'onClickItem'),
                    'zone_hero': this.initStockHero("zone_hero", true),
                    'zone_draft': this.initStockHero("draft_card", true, 'onSelectHero'),
                }

                this.guildLeader = {
                    'party': this.initStockItemsGameDungeon("zone_leader_party", 'onCreateItem'),
                    'dungeon': this.initStockItemsGameDungeon("zone_leader_dungeon", 'onCreateItem'),
                }

                this.guildLeader.party.setSelectionMode(1);
                this.guildLeader.party.setSelectionAppearance('class');
                this.guildLeader.dungeon.setSelectionMode(1);
                this.guildLeader.dungeon.setSelectionAppearance('class');

                this.items.zone_draft.item_margin = 20;

                // Dungeon level counter && Delve counter
                this.dungeon_level = this.initCounter('dungeon_level', this.gamedatas.level);
                this.delve_counter = this.initCounter('delve_counter', this.gamedatas.delve);

                // Add dice all over the board
                this.addItemsToBoard(gamedatas.items);

                // Setup game notifications to handle (see "setupNotifications" method below)
                this.setupNotifications();

                // Add tooltips to navigation
                Object.keys(this.gamedatas.phases).forEach(phase => {
                    this.addTooltipHtml("nav_" + phase, this.getItemTooltip(this.gamedatas.phases[phase]))
                });

                // Set hero activation
                this.setIsHeroActivated(gamedatas.isHeroActivated);

                // Show specialty
                if (gamedatas.heroes && gamedatas.heroes[this.getActivePlayerId()]) {
                    var hero = gamedatas.heroes[this.getActivePlayerId()];
                    var type = hero.type + '_' + hero.value;
                    this.showSpecialty(type);
                }

                // Display Score button
                if(!this.isSpectator) {
                    var playerBoard = dojo.query("#player_board_actions_" + this.player_id)[0];
                    dojo.place(dojo.create("button", {
                        class: "display-score", 
                        innerHTML: _('Display scores')
                    }), playerBoard);
                    dojo.query(".display-score").connect("click", this, "displayScore");
                }
            },

            displayScore() {
                this.ajaxcall("/dungeonroll/dungeonroll/displayScore.html", {
                    lock: true
                }, this, function(result) {});
            },

            setIsHeroActivated: function(isActivated) {
                var div = dojo.query('#zone_hero');
                if (isActivated) {
                    div.addClass("activated");
                } else {
                    div.removeClass("activated");
                }
            },

            initCounter(div_name, initialValue) {
                var counter = new ebg.counter();
                counter.create(div_name);
                counter.setValue(initialValue);
                return counter;
            },

            getItemTooltip: function(item) {

                var gametext = "";

                if (item.tooltip !== undefined) {
                    var textArray = [];

                    item.tooltip.forEach(text => {
                        // Translate each tooltip
                        textArray.push(_(text));
                    });

                    gametext = textArray.join("<br/><br/>");
                }

                var itemInfo = {
                    'name': _(item.name),
                    'text': gametext,
                };

                return this.format_block('jstpl_item_tooltip', itemInfo);
            },

            getUltimateName: function(card) {
                var textUltimate = _('Ultimate');
                if (card.ultimate_name !== undefined) {
                    textUltimate = _(card.ultimate_name);
                }
                return textUltimate;
            },

            getCardTooltip: function(card_type_id) {

                var card = this.gamedatas.card_types[card_type_id];

                var cardInfo = {
                    'ultimate': '<div class="keyword">' + this.getUltimateName(card) + ' <br/> </div>' + _(card.ultimate),
                    'specialty': '<div class="keyword">' + _('Specialty') + ' <br/> </div>' + _(card.specialty),
                    'name': _(card.name)
                };

                if (card_type_id.split('_')[0] == this.ItemType.HeroNovice) {
                    cardInfo['xp_level_up'] = '<div class="keyword">' + _('5 XP to Level up') + '</div>'
                    return this.format_block('jstpl_hero_novice_tooltip', cardInfo);
                } else {
                    return this.format_block('jstpl_card_tooltip', cardInfo);
                }

            },

            setupHeroes: function(card_div, card_type_id, card_id) {
                this.setupTooltipHeroes(card_div, card_type_id, card_id);
            },

            setupTooltipHeroes: function(card_div, card_type_id, card_id) {

                if (card_type_id != 0 && card_type_id !== undefined) {
                    var card_hero_number = card_type_id.split('_')[1];
                    var novice_number = this.ItemType.HeroNovice + '_' + card_hero_number;
                    var master_number = this.ItemType.HeroMaster + '_' + card_hero_number;
                    var card = this.gamedatas.card_types[card_type_id];
                    var index = toint(card.imageindex) - 1;

                    var cardNovice = this.gamedatas.card_types[novice_number];
                    var cardMaster = this.gamedatas.card_types[master_number];

                    if (cardMaster !== undefined) {
                        var args = {
                            artx: 297 * (index % 8),
                            arty: 425 * (Math.floor(index / 8)),
                            'specialty': _('Specialty'),
                            'ultimate': _('Ultimate'),
                            'ultimate_name_novice': this.getUltimateName(cardNovice),
                            'ultimate_name_master': this.getUltimateName(cardMaster),
                            'specialty_novice': _(cardNovice.specialty),
                            'ultimate_novice': _(cardNovice.ultimate),
                            'name_novice': _(cardNovice.name),
                            'specialty_master': _(cardMaster.specialty),
                            'ultimate_master': _(cardMaster.ultimate),
                            'name_master': _(cardMaster.name)
                        };

                        var html = this.format_block('jstpl_hero_tooltip', args);

                        this.addTooltipHtml(card_div.id, html, 100);
                    } else {

                        var args = {
                            artx: 297 * (index % 8),
                            arty: 425 * (Math.floor(index / 8)),
                            'specialty': _('Specialty'),
                            'ultimate': _('Ultimate'),
                            'ultimate_name': this.getUltimateName(cardNovice),
                            'specialty_novice': _(cardNovice.specialty),
                            'ultimate_novice': _(cardNovice.ultimate),
                            'name_novice': _(cardNovice.name),
                        };

                        var html = this.format_block('jstpl_hero_golden_tooltip', args);

                        this.addTooltipHtml(card_div.id, html, 100);
                    }
                }
            },

            initStockInventory: function(zone) {
                var stock = new ebg.stock();
                stock.create(this, $(zone), 25, 25);

                stock.setSelectionMode(0);
                stock.image_items_per_row = 6;

                // Load images
                Object.keys(this.gamedatas.items_treasure_tokens).forEach(key => {
                    var die = this.gamedatas.items_treasure_tokens[key];
                    stock.addItemType(key, die.weight, g_gamethemeurl + 'img/treasures.png', die.image_index);
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

                heroZone.setSelectionMode(0);
                heroZone.image_items_per_row = 8;

                Object.keys(this.gamedatas.card_types).forEach(card_type => {
                    var card = this.gamedatas.card_types[card_type];
                    heroZone.addItemType(card_type, 1, g_gamethemeurl + 'img/bigcards.jpg', card.imageindex - 1);
                });

                if (callback == undefined) {
                    heroZone.onItemCreate = dojo.hitch(this, 'setupHeroes');
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

                stock.setSelectionMode(0);
                stock.image_items_per_row = 6;
                stock.item_margin = 10;

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

                div.addClass("item_" + card_type_id);

                var itemType = card_type_id.split("_")[0];
                if (itemType == 1) {
                    div.addClass("die")
                    div.addClass("party")
                } else if (itemType == 2) {
                    div.addClass("die")
                    div.addClass("dungeon")
                } else if (itemType == 3) {
                    div.addClass("token")
                }

                var itemType = "";
                switch (card_type_id.split("_")[0]) {
                    case "1":
                        itemType = this.gamedatas.items_party_dice[card_type_id];
                        break;
                    case "2":
                        itemType = this.gamedatas.items_dungeon_dice[card_type_id];
                        break;
                    case "3":
                        itemType = this.gamedatas.items_treasure_tokens[card_type_id];
                        break;
                }

                var html = this.getItemTooltip(itemType);
                this.addTooltipHtml(card_id, html, 1000);

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
                        dojo.style('zone_draft', 'display', 'block');
                        dojo.style('board', 'display', 'none');
                        this.items['zone_draft'].removeAll();
                        args.args.heroes.forEach(hero => {
                            var type = hero['type'] + '_' + hero['value'];
                            this.items['zone_draft'].addToStockWithId(type, hero['id']);
                        });
                        break;

                    case 'initPlayerTurn':
                        dojo.style('zone_draft', 'display', 'none');
                        dojo.style('board', 'display', 'block');
                        break;

                    case 'guildLeaderUltimate':
                        if (this.isCurrentPlayerActive()) {
                            dojo.style('divUltimateLeader', 'display', 'block');
                            this.showUltimateLeaderButtons(args.args);
                        }
                        break;

                    case 'dummmy':
                        break;
                }
            },

            // onLeavingState: this method is called each time we are leaving a game state.
            //                 You can use this method to perform some user interface changes at this moment.
            //
            onLeavingState: function(stateName) {
                switch (stateName) {
                    case 'guildLeaderUltimate':
                        dojo.style('divUltimateLeader', 'display', 'none');
                        dojo.removeClass('divLeaderParty', 'hide');
                        dojo.removeClass('divLeaderDungeon', 'hide');

                    case 'dummmy':
                        break;
                }
            },

            // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
            //                        action status bar (ie: the HTML links in the status bar).
            //        
            onUpdateActionButtons: function(stateName, args) {

                dojo.query("li.selected").removeClass("selected");
                dojo.query("#nav_" + stateName).addClass("selected");

                if (this.isCurrentPlayerActive()) {
                    switch (stateName) {
                        case 'guildLeaderUltimate':
                            dojo.empty('zone_phases_actions');
                            dojo.empty('zone_actions');
                            this.addActionButton('cmdGuildLeaderSelction', _("Confirm the selection"), 'guildLeaderSelection', 'zone_actions');
                            break;
                        case 'quaffPotion':
                            dojo.query("#nav_" + args.currentPhase).addClass("selected");
                            this.showCommands(args.commands);
                            this.showDiceButton();
                            break;

                        default:
                            if (args.commands !== undefined) {
                                this.showCommands(args.commands);
                            }
                    }
                }
            },

            showUltimateLeaderButtons: function(args) {

                this.guildLeader.party.removeAll();
                this.guildLeader.dungeon.removeAll();

                for (let index = 1; index <= 6; index++) {
                    this.guildLeader.party.addToStockWithId(this.ItemType.Party + '_' + index, index);
                    this.guildLeader.dungeon.addToStockWithId(this.ItemType.Dungeon + '_' + index, index);
                }

                if (args.party[0] !== null && args.party[0] !== undefined) {
                    this.guildLeader.party.selectItem(args.party[0].value);
                } else {
                    dojo.addClass('divLeaderParty', 'hide');
                }

                if (args.dungeon[0] !== null && args.dungeon[0] !== undefined) {
                    this.guildLeader.dungeon.selectItem(args.dungeon[0].value);
                } else {
                    dojo.addClass('divLeaderDungeon', 'hide');
                }

            },

            showDiceButton: function() {
                Object.keys(this.gamedatas.items_party_dice).forEach(key => {
                    var die = this.gamedatas.items_party_dice[key];
                    if (die.small_icon !== undefined) {
                        this.addActionButton('dice_' + key, '<div class="sicon ' + die.small_icon + '"></div>', 'chooseDieGain', 'zone_actions', null, 'gray');
                    }
                });

            },

            showCommands: function(commands) {

                dojo.empty('zone_phases_actions');
                dojo.empty('zone_actions');
                this.removeActionButtons();

                if (commands === undefined) return;

                commands.forEach(command => {

                    if (command.isActive) {
                        if (command.commands === undefined) {
                            if (command.button_color) {
                                this.addActionButton('cmd_' + command.id, _(command.text), 'executeCommand', command['html_zone'], false, command.button_color);
                            } else {
                                this.addActionButton('cmd_' + command.id, _(command.text), 'executeCommand', command['html_zone']);
                            }
                        } else {
                            Object.keys(command.commands).forEach(key => {
                                this.addActionButton('cmd_' + command.id + '_' + key, _(command.commands[key]), 'executeCommand', command['html_zone']);
                            });
                        }
                        if (command['isActive'] == false) {
                            dojo.query('#cmd_' + command.id).addClass("disabled");
                        }
                    } else if (command.always_visible) {
                        if (command.button_color) {
                            this.addActionButton('cmd_' + command.id, _(command.text), 'executeCommand', command['html_zone'], false, command.button_color);
                        } else {
                            this.addActionButton('cmd_' + command.id, _(command.text), 'executeCommand', command['html_zone']);
                        }

                        if (command.isActive !== true) {
                            dojo.addClass('cmd_' + command.id, "disabled");
                        }
                    }
                });
            },



            onSelectHero: function(evt) {
                // Stop this event propagation
                evt.preventDefault();
                dojo.stopEvent(evt);
                if (this.checkAction('selectHero')) {
                    var hero_id = evt.currentTarget.id.substring(evt.currentTarget.id.lastIndexOf("_") + 1);
                    this.ajaxcall("/dungeonroll/dungeonroll/selectHero.html", {
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

                    var askedConfirmation = false;
                    if (command.confirmations !== undefined) {
                        Object.keys(command.confirmations).forEach(key => {

                            var question = command.confirmations[key];
                            if (this.canAskConfirmation(question)) {

                                askedConfirmation = true;

                                this.confirmationDialog(
                                    question.confirmation,
                                    dojo.hitch(this, function() {
                                        this.ajaxcall("/dungeonroll/dungeonroll/executeCommand.html", {
                                            id: command.id,
                                            sub: subCommand,
                                            lock: true
                                        }, this, function(result) {});
                                    }));
                            }
                        });
                    }

                    if (!askedConfirmation) {
                        this.ajaxcall("/dungeonroll/dungeonroll/executeCommand.html", {
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
                    this.ajaxcall("/dungeonroll/dungeonroll/chooseDieGain.html", {
                        type: diceType.split("_")[0],
                        value: diceType.split("_")[1],
                        lock: true
                    }, this, function(result) {});
                }
            },

            guildLeaderSelection: function(evt) {
                if (this.checkAction('selectGuildLeaderDice')) {

                    var args = {
                        party: 0,
                        dungeon: 0,
                        lock: true
                    };

                    if (this.guildLeader.party.getSelectedItems()[0] !== undefined) {
                        args.party = this.guildLeader.party.getSelectedItems()[0].id
                    }

                    if (this.guildLeader.dungeon.getSelectedItems()[0] !== undefined) {
                        args.dungeon = this.guildLeader.dungeon.getSelectedItems()[0].id
                    }

                    this.ajaxcall("/dungeonroll/dungeonroll/selectGuildLeaderDice.html", args, this, function(result) {});
                }
            },

            ///////////////////////////////////////////////////
            //// Utility methods

            /*
             
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
             
            */

            /** Override this function to inject html for log items  */

            /* @Override */
            format_string_recursive: function(log, args) {
                try {
                    if (log && args && !args.processed) {
                        args.processed = true;

                        var keys = ['items_log', 'items_log_1', 'items_log_2'];

                        for (var i in keys) {
                            var key = keys[i];
                            if (Array.isArray(args[key])) {
                                args[key] = this.getDiceDiv(args[key]);
                            }
                        }

                        args['experience'] = '<i class="fa fa-star"></i>';

                        var keys2 = ['hero_name', 'hero_novice_name', 'hero_master_name'];

                        for (var i in keys2) {
                            var key = keys2[i];
                            if (typeof args[key] == 'string') {
                                args[key] = '<span class="hero-name">' + _(args[key]) + '</span>';
                            }
                        }
                        // Normal translate
                        Object.keys(args).forEach(key => {
                            if (key.endsWith('_tr')) {
                                args[key] = _(args[key])
                            }
                        });

                    }
                } catch (e) {
                    console.error(log, args, "Exception thrown", e.stack);
                }
                return this.inherited(arguments);
            },

            getDiceDiv: function(items) {
                var html = [];
                var infoItems = [];

                items.forEach(item => {
                    infoItems.push(this.getItemInfo(item));
                });

                infoItems = infoItems.sort(function(a, b) {
                    if (a.weight > b.weight) return 1;
                    if (a.weight < b.weight) return -1;
                    return 0;
                });

                infoItems.forEach(info => {
                    var additionalClass = "";
                    if (info.id >= 1000 && info.id < 2000) {
                        additionalClass = "temp-item";
                    } else if (info.id >= 2000 && info.id <= 3000) {
                        additionalClass = "temp-ability";
                    }
                    html.push('<span class="item item_' + info.type + ' ' + additionalClass + '">' + _(info.name) + '</span>')
                });

                return html.join(" ");
            },

            getItemInfo(item) {
                var typeValue = item.type + '_' + item.value;
                switch (parseInt(item.type)) {

                    case 1:
                        return Object.assign({ type: typeValue, id: item.id }, this.gamedatas.items_party_dice[typeValue]);

                    case 2:
                        return Object.assign({ type: typeValue, id: item.id }, this.gamedatas.items_dungeon_dice[typeValue]);

                    case 3:
                        return Object.assign({ type: typeValue, id: item.id }, this.gamedatas.items_treasure_tokens[typeValue]);
                }
            },

            canAskConfirmation: function(command) {
                switch (command.askConfirmation) {
                    case 'checkRerollPotion':
                        return this.checkRerollPotion();
                    case 'checkRerollDragon':
                        return this.checkRerollDragon();
                    case 'checkRerollDragonCommander':
                        return this.checkRerollDragonCommander();
                    case 'checkNonSelectedChest':
                        return this.checkNonSelectedChest();
                    case 'checkNonSelectedPotion':
                        return this.checkNonSelectedPotion();
                    case 'checkFightGoblin':
                        return this.checkFightGoblin();
                    case 'checkFightOoze':
                        return this.checkFightOoze();
                    case 'checkFightSkeleton':
                        return this.checkFightSkeleton();
                    case 'always':
                        return true;
                    default:
                        return false;
                }
            },

            checkRerollPotion: function() {
                var item_types = this.items.zone_play.getPresentTypeList();
                return item_types[this.DungeonType.Potion] == 1; // Potions
            },

            checkRerollDragon: function() {
                var item_types = this.items.zone_play.getPresentTypeList();
                return item_types[this.DungeonType.Dragon] == 1; // Dragons
            },

            checkRerollDragonCommander: function() {
                var item_types = this.items.zone_play.getPresentTypeList();
                var item_types_hero = this.items.zone_hero.getPresentTypeList();
                return item_types[this.DungeonType.Dragon] == 1 &&
                    item_types_hero["5_2"] == 1; // Hero Master Commander
            },

            checkNonSelectedChest: function() {
                var item_types_play = this.items.zone_play.getPresentTypeList();
                var item_types_dungeon = this.items.zone_dungeon.getPresentTypeList();
                var thiefOrChampionPresent =
                    item_types_play[this.PartyType.Thief] == 1 ||
                    item_types_play[this.InventoryType.Thieves] == 1 ||
                    item_types_play[this.PartyType.Champion] == 1;
                return item_types_dungeon[this.DungeonType.Chest] == 1 &&
                    thiefOrChampionPresent;
            },

            checkFightGoblin: function() {
                var item_types_play = this.items.zone_play.getPresentTypeList();
                var item_types_hero = this.items.zone_hero.getPresentTypeList();

                return Object.keys(item_types_play).length == 2 &&
                    this.diceInPlayAndDungeon(this.DungeonType.Goblin) &&
                    (
                        (
                            item_types_play[this.PartyType.Champion] == 1 &&
                            item_types_hero["4_20"] !== 1 // Marpesia, Amazon Queen
                        ) ||
                        item_types_play[this.PartyType.Fighter] == 1 ||
                        item_types_play[this.InventoryType.VorpalSword] == 1
                    );
            },

            checkFightOoze: function() {
                var item_types_hero = this.items.zone_hero.getPresentTypeList();
                var item_types_play = this.items.zone_play.getPresentTypeList();

                return Object.keys(item_types_play).length == 2 &&
                    this.diceInPlayAndDungeon(this.DungeonType.Ooze) &&
                    (
                        (
                            item_types_play[this.PartyType.Champion] == 1 &&
                            item_types_hero["4_20"] !== 1 // Marpesia, Amazon Queen
                        ) ||
                        item_types_play[this.PartyType.Mage] == 1 ||
                        item_types_play[this.InventoryType.ScepterOfPower] == 1
                    );
            },

            checkFightSkeleton: function() {
                var item_types_play = this.items.zone_play.getPresentTypeList();
                var item_types_hero = this.items.zone_hero.getPresentTypeList();

                return Object.keys(item_types_play).length == 2 &&
                    this.diceInPlayAndDungeon(this.DungeonType.Skeleton) &&
                    (
                        (
                            item_types_play[this.PartyType.Champion] == 1 &&
                            item_types_hero["4_20"] !== 1 // Marpesia, Amazon Queen
                        ) ||
                        item_types_play[this.PartyType.Cleric] == 1 ||
                        item_types_play[this.InventoryType.Talisman] == 1
                    );
            },

            diceInPlayAndDungeon: function(dungeonType) {
                var item_types_play = this.items.zone_play.getPresentTypeList();
                var item_types_dungeon = this.items.zone_dungeon.getPresentTypeList();

                return item_types_play[dungeonType] == 1 && item_types_dungeon[dungeonType] == 1;
            },

            checkNonSelectedPotion: function() {
                var item_types_dungeon = this.items.zone_dungeon.getPresentTypeList();
                return item_types_dungeon[this.DungeonType.Potion] == 1;
            },

            showSpecialty: function(card_type_id) {
                var card = this.gamedatas.card_types[card_type_id];
                var gametext = "<span>" + _(card.specialty) + "</span>";
                dojo.empty('zone_specialty');
                dojo.place(gametext, 'zone_specialty');
            },

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
                if (this.checkAction('moveItem')) {
                    this.ajaxcall("/dungeonroll/dungeonroll/moveItem.html", {
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
            your dungeonroll.game.php file.
             
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
                dojo.subscribe('onHeroUltimate', this, "notif_onHeroUltimate");
                dojo.subscribe('onHeroRefresh', this, "notif_onHeroRefresh");
                dojo.subscribe('onSelectHero', this, "notif_onSelectHero");

                this.notifqueue.setSynchronous('onDiceRolled', 1250);

            },

            notif_updateScores: function(notif) {
                for (var player_id in notif.args.scores) {
                    this.scoreCtrl[player_id].toValue(notif.args.scores[player_id]);
                }
            },

            notif_onItemsMoved: function(notif) {

                notif.args.items.forEach(item => {
                    var item_type = item.type + "_" + item.value;

                    if (item.previous_zone == 'box' || this.isStringEmpty(item.previous_zone)) {
                        this.items['zone_' + item.zone].addToStockWithId(item_type, item.id, $("zone_graveyard"));
                        if (item.type == this.ItemType.Token && item.owner) {
                            var board = this.player_board[item.owner];
                            board.inventory.addToStockWithId(item_type, item.id);
                        }
                    } else if (item.zone == 'box' || this.isStringEmpty(item.zone)) {
                        var divId = this.items['zone_' + item.previous_zone].getItemDivId(item.id);
                        this.removeTooltip(divId);

                        var from = $(this.items['zone_' + item.previous_zone].getItemDivId(item.id));
                        this.slideToObjectAndDestroy(from, $("zone_graveyard"), 1000, 0);
                        this.items['zone_' + item.previous_zone].removeFromStockById(item.id);

                        if (item.type == this.ItemType.Token && item.owner) {
                            var board = this.player_board[item.owner];
                            board.inventory.removeFromStockById(item.id);
                        }
                    } else if (item.previous_zone == item.zone) {
                        var divId = this.items['zone_' + item.zone].getItemDivId(item.id);
                        this.removeTooltip(divId);

                        this.items['zone_' + item.zone].removeFromStockById(item.id);
                        this.items['zone_' + item.zone].addToStockWithId(item_type, item.id);
                    } else {
                        var divId = this.items['zone_' + item.previous_zone].getItemDivId(item.id);
                        this.removeTooltip(divId);

                        var from = $(this.items['zone_' + item.previous_zone].getItemDivId(item.id));
                        this.items['zone_' + item.zone].addToStockWithId(item_type, item.id, from);
                        this.items['zone_' + item.previous_zone].removeFromStockById(item.id);
                    }

                    if (item.type == this.ItemType.HeroNovice || item.type == this.ItemType.HeroMaster) {
                        this.showSpecialty(item_type);
                    }

                });

            },

            notif_onHeroLevelUp: function(notif) {
                var masterHeroType = "5_" + notif.args.hero_master['value'];

                var heroZone = this.player_board[notif.args.player_id].hero;
                heroZone.removeAll();
                heroZone.addToStockWithId(masterHeroType, notif.args.hero_master['id']);
                this.showSpecialty(masterHeroType);

                // if the player who hero level up is the current player
                if (notif.args.player_id == this.player_id) {
                    // Display
                    var card = this.gamedatas.card_types[masterHeroType];
                    var index = toint(card.imageindex) - 1;
                    var args = {
                        artx: 297 * (index % 8),
                        arty: 425 * (Math.floor(index / 8)),
                        title: _("Your hero leveled up")
                    };

                    var html = this.format_block('jstpl_hero_level_up', args);
                    dojo.place(html, 'board', 'first');
                    this.fadeOutAndDestroy('dlg', 1000, 3000)
                }
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
                this.setIsHeroActivated(false);
                this.showCommands([]);
            },

            notif_onNewLevel: function(notif) {
                this.dungeon_level.setValue(notif.args.level);
            },

            notif_onNewDelve: function(notif) {
                this.delve_counter.setValue(notif.args.delve_number);
                this.player_board[notif.args.player_id].delve.setValue(notif.args.delve_number);
                this.dungeon_level.setValue(1);
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

            notif_onHeroUltimate: function(notif) {
                this.setIsHeroActivated(true);
            },

            notif_onHeroRefresh: function(notif) {
                this.setIsHeroActivated(false);
            },

            notif_onSelectHero: function(notif) {
                var hero = notif.args.hero;
                if (hero.previous_zone !== undefined) {
                    // Happen in standard game (or the player you select his hero)
                    var from = $(this.items['zone_' + hero.previous_zone].getItemDivId(hero.id));
                    this.player_board[hero['owner']].hero.addToStockWithId(hero.type + "_" + hero.value, hero.id, from);
                    this.items['zone_' + hero.previous_zone].removeFromStockById(hero.from);
                } else {
                    // Happen in mirror game for others players that not select his hero
                    this.player_board[hero['owner']].hero.addToStockWithId(hero.type + "_" + hero.value, hero.id);
                }
            },

            isStringEmpty: function(value) {
                return value === undefined || value === null || value === '';
            }

        });
    });