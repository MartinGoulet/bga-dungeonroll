<?php

class DRItemManager extends APP_GameClass
{
    private $game;

    function __construct($game)
    {
        $this->game = $game;
    }

    function createGameComponents($components, $cards)
    {
        $sql = "INSERT INTO item (item_type, item_value, item_name, item_zone) VALUES ";
        $sql_values = array();

        foreach ($components as $item) {
            for ($i = 0; $i < $item['number']; $i++) {
                $type = $item['type'];
                $value = $item['value'];
                $name = $item['name'];
                $sql_values[] = "($type, $value, '$name', 'box')";
            }
        }

        $gameExpansion = $this->game->vars->getGameExpansion();
        $filteredCards = array_filter($cards, function($card) use($gameExpansion) {
            return in_array($gameExpansion, $card['expansions']);
        });

        foreach ($filteredCards as $card_id => $card) {
            $card_type = explode("_", $card_id)[0];
            $card_value = explode("_", $card_id)[1];
            $card_name = str_replace("'", "\'", $card['name']);
            $sql_values[] = "($card_type, $card_value, '$card_name', 'box')";
        }

        $sql .= implode(',', $sql_values);
        self::DbQuery($sql);
    }

    function addNewHero($heroes) {

        $sql = "INSERT INTO item (item_type, item_value, item_zone, owner_id) VALUES ";
        $sql_values = array();

        foreach ($heroes as $hero) {
            $card_type = $hero['type'];
            $card_value = $hero['value'];
            $card_zone = $hero['zone'];
            $card_owner = $hero['owner'];
            if($hero['owner'] == null) {
                $card_owner = 'null';
            }
            $sql_values[] = "($card_type, $card_value, '$card_zone', $card_owner)";
        }

        $sql .= implode(',', $sql_values);
        self::DbQuery($sql);

        $this->game->components->updateItems();

    }

    function getAllItems()
    {
        $sql = "SELECT item_id id, item_value value, item_type type, item_zone zone, owner_id owner FROM item";
        return self::getObjectListFromDB($sql);
    }

    function getInventoryByPlayer($players) {
        $inventories = array();
        foreach ($players as $player_id => $player) {
            $inventories[$player_id] = $this->game->components->getItemsByPlayerAndType($player_id, TYPE_TREASURE_TOKEN);
        }
        return $inventories;
    }

    function resetDice()
    {
        $owner_id = $this->game->getActivePlayerId();
        $sql = "UPDATE item SET item_value = 0, item_zone = 'box', owner_id = $owner_id WHERE item_type IN (1, 2)";
        self::DbQuery($sql);

        $this->game->components->updateItems();
    }

    function insertTemporaryItem(&$item)
    {
        $id = self::getUniqueValueFromDB("SELECT max(item_id) FROM item 
                                          WHERE item_id >= 1000 AND item_id < 2000");
        if ($id == null) {
            $id = 1000;
        } else {
            $id = $id + 1;
        }

        $item['id'] = $id;
        $type = $item['type'];
        $value = $item['value'];
        $zone = $item['zone'];
        $owner = $item['owner'];

        $sql = "INSERT INTO item (item_id, item_type, item_value, item_zone, owner_id)
                VALUES ($id, $type, $value, '$zone', $owner)";

        self::DbQuery($sql);

        $this->game->components->updateItems();
    }

    function insertTemporaryAbility(&$item)
    {
        $id = self::getUniqueValueFromDB("SELECT max(item_id) FROM item 
                                          WHERE item_id >= 2000 AND item_id < 3000");
        if ($id == null) {
            $id = 2000;
        } else {
            $id = $id + 1;
        }

        $item['id'] = $id;
        $type = $item['type'];
        $value = $item['value'];
        $zone = $item['zone'];
        $owner = $item['owner'];

        $sql = "INSERT INTO item (item_id, item_type, item_value, item_zone, owner_id)
                VALUES ($id, $type, $value, '$zone', $owner)";

        self::DbQuery($sql);

        $this->game->components->updateItems();
    }

    function deleteTemporaryItem()
    {
        self::DbQuery("DELETE FROM item WHERE item_id >= 1000 && item_id < 2000");
    }



    function deleteTemporaryAbility()
    {
        self::DbQuery("DELETE FROM item WHERE item_id >= 2000 && item_id < 3000");
    }

    function updateItems(&$items)
    {
        $items = array_values($items);

        if (sizeof($items) == 0) {
            return;
        }

        // Check for temporary item goes to the graveyard
        $updates = array();
        foreach ($items as $item) {
            if ($item['zone'] == ZONE_GRAVEYARD) {
                if (DRItem::isTemporaryItem($item) || DRItem::isTemporaryAbility($item)) {
                    $item['zone'] = ZONE_BOX;
                    $item['owner'] = null;
                }
            }
            $updates[] = $item;
        }
        $items = $updates;

        $sql_values = array();

        foreach ($items as $item) {
            $item_id = $item['id'];
            $owner_id = $item['owner'];
            $item_zone = $item['zone'];
            $item_value = $item['value'];
            if ($owner_id == null)
                $owner_id = "null";

            if ($item_zone == null || $item_zone == '') {
                $sql_values[] = "SELECT $item_id as item_id, $owner_id as owner_id, $item_value as item_value, 'box' as item_zone";
            } else {
                $sql_values[] = "SELECT $item_id as item_id, $owner_id as owner_id, $item_value as item_value, '$item_zone' as item_zone";
            }
        }

        $sql_union = implode(" UNION ALL ", $sql_values);

        $sql = "UPDATE item AS dest, ($sql_union) AS src
                   SET dest.owner_id   = src.owner_id,
                       dest.item_zone  = src.item_zone,
                       dest.item_value = src.item_value
                 WHERE dest.item_id    = src.item_id;";

        self::DbQuery($sql);

        $this->game->components->updateItems();
    }
}
