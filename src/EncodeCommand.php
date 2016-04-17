<?php
namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EncodeCommand extends Command
{
    protected function configure()
    {
        $this->setName('encode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input = 'Hello, world!';
        $encoded = '';
        $map = [];

        foreach (str_split($input) as $char) {
            if (!ctype_alpha($char)) {
                $encoded .= $char;
                continue;
            }

            if (isset($map[$char])) {
                $encoded .= $map[$char];
                continue;
            }

            $map[$char] = $this->createCode($char);
            $encoded .= $map[$char];
        }

        foreach ($map as $key => $value) {
            $output->write($key.' '.$value.' ');
        }
        $output->write("\n");
        $output->writeLn($encoded);
    }

    protected function createCode($char)
    {
        return str_replace(['1', '0'], ['g', 'G'], decbin(ord($char)));
    }
}
