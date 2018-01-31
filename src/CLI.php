<?php
namespace SCG\Component\CLI;

use SCG\Component\CLI\Command;
use SCG\Component\CLI\Commands;

class CLI
{

    private $commands;

    public function __construct ()
    {
        $this->commands = new Commands($this);
    }

    public function run ()
    {
        $this->prompt();
        while ($input = $this->read()) {

            $data = $this->parse($input);
            if ($this->commands->has($data['command'])) {
                $this->commands->call($data['command'], $data['arguments'], $data['options']);
            } else {
                $this->errorMessage('Unrecognized function \''.$data['command'].'\'');
            }

            $this->prompt();
        }
    }

    public function addCommand (Command $command)
    {
        return $this->commands->add($command);
    }

    public function removeCommand ($name)
    {
        return $this->commads->remove($name);
    }

    public function hasCommand ($name)
    {
        return $this->commands->has($name);
    }

    public function write ($text='', $newline='') {
        if (is_string($newline)) {
            $text .= $newline;
        }
        fwrite(STDOUT, "$text"); // Output - prompt user
    }

    public function prompt ($text='') {
        $this->write('> '.$text);
    }

    public function message ($text='') {
        $this->write($text, PHP_EOL);
    }

    public function errorMessage ($text='') {
        $this->message("Error: ".$text);
    }

    public function read () {
        return trim(fgets(STDIN));
    }

    private function parse ($input)
    {
        $parts = explode(' ', $input);

        $command = array_shift($parts);

        $data = [
            'command' => $command,
            'arguments' => [],
            'options' => []
        ];

        foreach ($parts as $part) {
            if (empty($part)) {
                continue;
            }

            // Check if part is an option
            if (strpos('--', $part) === 0 || strpos('-', $part) === 0) {
                $option = explode('=', $part);

                $key = ltrim($option[0], '-');
                $value = null;
                if (array_key_exists(1, $option)) {
                    $value = $option[1];
                }

                $data['options'][$key] = $value;
            } else {
                $data['arguments'][] = $part;
            }
        }

        return $data;
    }
}