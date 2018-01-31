<?php
namespace SCG\Component\CLI;

use SCG\Component\CLI\CLI;

class Command
{

    private $name;
    private $command;

    public function __construct ($name = null, $command = null)
    {
        $this->setName($name);

        if (is_callable($command)) {
            $this->setCommand($command);
        }
    }

    public function getName ()
    {
        return $this->name;
    }

    public function setName ($name)
    {
        if (is_string($name) && !empty($name)) {
            $this->name = $name;
        } else {
            // throw new CommandException('Invalid command name `'.$name.'`');
        }
    }

    public function setCommand (callable $command)
    {
        $this->command = $command;
    }

    public function call (CLI $cli, array $args = [], array $options = [])
    {
        call_user_func($this->command, $cli, $args, $options);
    }
}