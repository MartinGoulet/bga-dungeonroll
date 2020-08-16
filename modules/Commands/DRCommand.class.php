<?php

class DRCommand
{
    protected $game;
    protected $command_info;

    public function __construct($game, $command_info)
    {
        $this->game = $game;
        $this->command_info = $command_info;
    }

    function getCommandName() {
        return $this->command_info['name'];
    }

    function getCommandInfo() {
        return $this->command_info;
    }

    function isAllowedState() {
        return in_array($this->getState(), $this->getAllowedStates());
    }

    protected function getState() {
        return $this->game->gamestate->state()['name'];
    }

    protected function getAllowedStates() 
    {
        throw new Exception('Must implement');
    }

    public function canExecute() {
        return $this->isAllowedState();
    }
   
}