<?php


class DRComponentManager
{
    

    private $items = null;
    private $game;

    function __construct($game)
    {
        $this->game = $game;
    }

    function getAllItems()
    {
        if ($this->items == null) {
            $this->items = $this->game->manager->getAllItems();
        }
        return $this->items;
        // return $this->game->manager->getAllItems();
    }

    function getHeroesByPlayer() {
        $heroes = DRUtils::filter($this->getAllItems(), function ($item) {
            return $item['owner'] > 0 && $item['zone'] == 'hero';
        });
        $return = array();
        foreach ($heroes as $hero) {
            $return[$hero['owner']] = $hero;
        }
        return $return;
    }

    function getItemsByPlayer($player_id)
    {
        return DRUtils::filter($this->getAllItems(), function ($item) use ($player_id) {
            return $item['owner'] == $player_id && $item['zone'] != 'box';
        });
    }

    function getItemsByPlayerAndType($player_id, $type)
    {
        return DRUtils::filter($this->getAllItems(), function ($item) use ($player_id, $type) {
            return $item['owner'] == $player_id && $item['type'] == $type && $item['zone'] != ZONE_BOX;
        });
    }

    function getItemsByZone($zone)
    {
        return DRUtils::filter($this->getAllItems(), function ($item) use ($zone) {
            return $item['zone'] == $zone;
        });
    }

    function getItemsByType($type)
    {
        return DRUtils::filter($this->getAllItems(), function ($item) use ($type) {
            return $item['type'] == $type && $item['id'] < 1000;
        });
    }



    function getItemById($id)
    {
        return DRUtils::filter($this->getAllItems(), function ($item) use ($id) {
            return $item['id'] == $id;
        })[0];
    }

    function getItemsByTypeAndZone($type, $zone)
    {
        return DRUtils::filter($this->getAllItems(), function ($item) use ($type, $zone) {
            return $item['type'] == $type && $item['zone'] == $zone && $item['id'] < 1000;
        });
    }

    function getItemsByTypeAndValue($type, $value)
    {
        return DRUtils::filter($this->getAllItems(), function ($item) use ($type, $value) {
            return $item['type'] == $type && $item['value'] == $value && $item['id'] < 1000;
        });
    }

    function getActivePlayerItems()
    {
        return $this->getItemsByPlayer($this->game->getActivePlayerId());
    }

    function getActivePlayerUsableItems()
    {
        $includedZones = array(ZONE_PARTY, ZONE_DUNGEON, ZONE_INVENTORY, ZONE_PLAY, ZONE_DRAGON_LAIR);
        return DRUtils::filter($this->getActivePlayerItems(), function ($item) use ($includedZones) {
            return in_array($item['zone'], $includedZones);
        });
    }

    function getActivePlayerItemsByZone($zone)
    {
        return DRUtils::filter($this->getActivePlayerItems(), function ($item) use ($zone) {
            return $item['zone'] == $zone;
        });
    }

    function getActivePlayerCompanionsByZone($zone)
    {
        $items = $this->getActivePlayerItemsByZone($zone);
        return DRUtils::filter($items, function ($item) {
            return DRItem::isCompanionDice($item) || DRItem::isCompanionToken($item);
        });
    }

    function updateItems()
    {
        // To refresh the next query
        $this->items = null;
    }

    function getActivePlayerHero()
    {
        $currentHeroes = $this->getActivePlayerItemsByZone(ZONE_HERO);
        return DRHeroesManager::getHero(array_shift($currentHeroes), $this->game);
    }

    function getNoviceHeroes()
    {
        return DRUtils::filter($this->getItemsByType(TYPE_NOVICE_HERO), function ($item) {
            return $item['owner'] == null;
        });
    }
}
