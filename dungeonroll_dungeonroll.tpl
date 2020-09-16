{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- DungeonRoll implementation : © Martin Goulet <martin.goulet@live.ca>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------

    dungeonroll_dungeonroll.tpl
    
    This is the HTML template of your game.
    
    Everything you are writing in this file will be displayed in the HTML page of your game user interface,
    in the "main game zone" of the screen.
    
    You can use in this template:
    _ variables, with the format {MY_VARIABLE_ELEMENT}.
    _ HTML block, with the BEGIN/END format
    
    See your "view" PHP file to check how to set variables and control blocks
    
    Please REMOVE this comment before publishing your game on BGA
-->

<div id="zone_draft" style="display: none">
    <div class="whiteblock">
        <h3>{AVAILABLE_HEROES}</h3>
        <div id="draft_card"></div>
    </div>
</div>

<div id="board">
    <div class="row">
        <div class="col_4 whiteblock" style="padding: 0px">
            <div id="zone_actions"></div>
        </div>
    </div>

    <div id="divUltimateLeader">
        <div class="whiteblock">
            <div id="divLeaderParty">
                <div class="dicezone" id="zone_leader_party"></div>
            </div>
            <div id="divLeaderDungeon">
                <div class="dicezone" id="zone_leader_dungeon"></div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: -5px;">
        <div class="col_1 mr3" style="min-width: 230px">

            <div class="row">
                <div class="col_1 whiteblock h82">
                    <h3 style="margin-top: 0px;">{ZONE_SPECIALTY}</h3>
                    <div id="zone_specialty"></div>
                </div>
            </div>

        
            <div class="whiteblock mt0" style="min-height: 152px">
                <div class="card-hero">
                    <div id="zone_hero"></div>
                </div>
            </div>
        </div>

        <div class="col_2 ml3 mr3">

            <div class="whiteblock h82">
                <ul class="navigation">
                    <li id="nav_monsterPhase" class="phase monster"></li>
                    <li id="nav_lootPhase" class="phase loot"></li>
                    <li id="nav_dragonPhase" class="phase dragon"></li>
                    <li id="nav_regroupPhase" class="phase regroup"></li>
                </ul>
                <div id="zone_phases_actions"></div>
            </div>

            <div class="whiteblock" style="min-height: 152px">
                <h3>{ZONE_PLAYING_AREA}</h3>
                <div class="dicezone2" id="zone_play"></div>
            </div>

        </div>

        <div class="col_1 ml3" style="min-width: 230px">

            <div class="row">
                <div class="col_1 mr3 whiteblock h82">
                    <h3 style="margin-top: 0px;">{ZONE_DELVE}</h3>
                    <h3 class="counter">
                        <span id="delve_counter">1</span>
                    </h3>
                </div>
                <div class="col_1 ml3 whiteblock h82">
                    <h3 style="margin-top: 0px;">{ZONE_LEVEL}</h3>
                    <h3 class="counter">
                        <span id="dungeon_level">1</span>
                    </h3>
                </div>
            </div>

            <div class="whiteblock mt0" style="min-height: 152px">
                <h3>{ZONE_GRAVEYARD}</h3>
                <div class="dicezone2" id="zone_graveyard"></div>
            </div>
        </div>
    </div>


    <!-- Bottom Row -->
    <div class="row" style="margin-top: -5px;">
        <div class="col_4 mr3">

            <div class="whiteblock">
                <h3>{ZONE_PARTY}</h3>
                <div class="dicezone" id="zone_party"></div>
            </div>

            <div class="whiteblock">
                <h3>{ZONE_INVENTORY}</h3>
                <div class="dicezone" id="zone_inventory"></div>
            </div>

        </div>

        <div class="col_4 ml3">

            <div class="whiteblock">
                <h3>{ZONE_DUNGEON}</h3>
                <div class="dicezone" id="zone_dungeon"></div>
            </div>

            <div class="whiteblock">
                <h3>{ZONE_DRAGON_LAIR}</h3>
                <div class="dicezone" id="zone_dragon_lair"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Javascript HTML templates
var jstpl_hero_level_up = '\
<div id="dlg" class="dlg_hero_level_up">\
    <div class="title">${title}</div>\
    <div><div class="img_hero master" style="background-position: -${artx}px -${arty}px;"></div></div>\
</div>';

var jstpl_card_tooltip = '\
    <div class="cardtooltip">\
        <h3>${name}</h3>\
        <hr/>\
        ${specialty}\
        <br/><br/>\
        ${ultimate}\
    </div>';

var jstpl_hero_tooltip = '\
<div class="herotooltip">\
<div class="right-panel">\
    <h3 class="novice">${name_novice}</h3>\
    <div class="keyword">${specialty}</div>${specialty_novice}\
    <div class="keyword">${ultimate}</div>${ultimate_novice}\
    \
    <h3 class="master">${name_master}</h3>\
    <div class="keyword">${specialty}</div>${specialty_master}\
    <div class="keyword">${ultimate}</div>${ultimate_master}\
</div>\
<div class="img_hero master" style="background-position: -${artx}px -${arty}px;"></div>\
</div>\
';

var jstpl_hero_novice_tooltip = '\
    <div class="cardtooltip">\
        <h3>${name}</h3>\
        <hr/>\
        ${specialty}\
        <br/><br/>\
        ${ultimate}\
        <br/><br/>\
        ${xp_level_up}\
    </div>';

var jstpl_item_tooltip = '\
<div class="itemtooltip">\
    <h3>${name}</h3>\
    <hr/>\
    ${text}\
</div>';

var jstpl_player_board = '\
<div class="player_board">\
    <div class="player_hero">\
        <div id="player_hero_${id}" class="col_1"></div>\
    </div>\
    <div class="player_info">\
        <div class="player_delve">${delve} # <span id="player_delve_${id}">0</span></div>\
        <div class="player_inventory" id="player_inventory_${id}"></div>\
    </div>\
</div>\
';

</script>

{OVERALL_GAME_FOOTER}