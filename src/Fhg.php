<?php
/**
 * Fallout Hacking Game
 * @link https://www.reddit.com/r/dailyprogrammer/comments/3qjnil/20151028_challenge_238_intermediate_fallout/
 */

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class Fhg extends Command
{
    public $difficulty;

    protected $_wordNum = [
        1 => 5,
        2 => 8,
        3 => 10,
        4 => 12,
        5 => 15,
    ];

    protected $_wordLen = [
        1 => 4,
        2 => 6,
        3 => 9,
        4 => 12,
        5 => 15,
    ];

    protected function configure()
    {
        $this->setName('play')
            ->setDescription('Fallout Hacking Game');
    }

    public function getWordLen()
    {
        return $this->_wordLen[$this->difficulty];
    }

    public function getWordNum()
    {
        return $this->_wordNum[$this->difficulty];
    }

    protected function _getWords()
    {
        $_words = file_get_contents('./words.txt');
        $_words = explode("\n", $_words);
        foreach ($_words as $word) {
            if (strlen($word) == $this->getWordLen()) {
                $words[] = $word;
            }
        }
        shuffle($words);
        return array_slice($words, 0, $this->getWordNum());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->difficulty = $this->_askDifficulty($input, $output);
        $words = $this->_getWords();
        $secret = $words[rand(0, count($words) - 1)];

        for ($i = 4; $i > 0; $i--) {
            $guess = $this->_ask($input, $output, $words);
            $diff = $this->_compare($secret, $guess);
            if ($diff == strlen($secret)) {
                $output->writeLn('Access granted!');
                return;
            }

            $output->writeLn("$diff matches. ".($i - 1)." tries left.");
        }

        $output->writeLn('Access denied!');
    }

    protected function _askDifficulty($input, $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Please enter difficulty (1-5). ');
        $question->setValidator(function ($answer) {
            if (!in_array($answer, [1, 2, 3, 4, 5])) {
                throw new \RuntimeException(
                    'Invalid difficulty.'
                );
            }

            return $answer;
        });

        return $helper->ask($input, $output, $question);
    }

    protected function _ask($input, $output, $words)
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion('Please select the correct Password.', $words);
        return $helper->ask($input, $output, $question);
    }

    protected function _compare($secret, $guess)
    {
        $count = 0;
        for ($i = 0; $i < strlen($secret); $i++) {
            if ($secret[$i] == $guess[$i]) {
                $count++;
            }
        }
        return $count;
    }
}
