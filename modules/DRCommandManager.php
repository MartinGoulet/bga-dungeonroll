<?php

use phpDocumentor\Reflection\Types\Array_;

require_once('Commands/DRCommand.class.php');
require_once('Commands/DRCommandUseScroll.class.php');
require_once('Commands/DRCommandFleeDungeon.class.php');
require_once('Commands/DRCommandOpenChest.class.php');
require_once('Commands/DRCommandDragonBait.class.php');
require_once('Commands/DRCommandTownPortal.class.php');
require_once('Commands/DRCommandRingInvisibility.class.php');
require_once('Commands/DRCommandSeekGlory.class.php');
require_once('Commands/DRCommandRetireTavern.class.php');
require_once('Commands/DRCommandEndLootPhase.class.php');
require_once('Commands/DRCommandEndMonsterPhase.class.php');
require_once('Commands/DRCommandFightDragon.class.php');
require_once('Commands/DRCommandFightMonster.class.php');
require_once('Commands/DRCommandHeroUltimate.class.php');
require_once('Commands/DRCommandQuaffPotion.class.php');
require_once('Commands/DRCommandUsePotion.class.php');
require_once('Commands/DRCommandEndFormingPartyPhase.class.php');
require_once('Commands/DRCommandRollFormingPartyPhase.class.php');

class DRCommandManager
{
    private $game;

    function __construct($game)
    {
        $this->game = $game;
    }

    function getCommand($id)
    {
        foreach ($this->game->getCommandInfos() as $idCommand => $command) {
            if ($idCommand == $id) {
                return new $command['php_class']($this->game, $command);
            }
        }
    }

    function getCommandByName($name)
    {
        foreach ($this->game->getCommandInfos() as $idCommand => $command) {
            if ($command['name'] == $name) {
                return new $command['php_class']($this->game, $command);
            }
        }
    }

    function getAllCommands()
    {
        $commands = array();

        foreach ($this->game->getCommandInfos() as $idCommand => $command) {
            $commands[] = new $command['php_class']($this->game, $command);
        }

        return $commands;
    }

    function getActiveCommands() {
        $commands = array();
        foreach ($this->getAllCommands() as $command) {
            if ($command->canExecute()) {
                $commands[] = $command->getCommandInfo();
            }
        }
        return $commands;
    }
}
