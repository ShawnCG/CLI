<?php
namespace SCG\Component\CLI;

use SCG\Component\CLI\Command;


class Commands
{
    private $cli;
    private $commands = [];

    public function __construct (CLI $cli)
    {
        $this->cli = $cli;
    }

    public function add (Command $command)
    {
        if (!$this->has($command->getName())) {
            $this->commands[$command->getName()] = $command;
        } else {
            // throw new CommandsException('Command `'.$name.'` is already defined.');
        }
    }

    public function remove ($name)
    {
        if (array_key_exists($name, $this->commands)) {
            unset($this->commands[$name]);
        }
    }

    public function call ($name, $args, $options)
    {
        if ($this->has($name)) {
            $this->commands[$name]->call($this->cli, $args, $options);
        }
    }


    public function has ($name)
    {
        return array_key_exists($name, $this->commands);
    }
}