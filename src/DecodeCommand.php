<?php
namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DecodeCommand extends Command
{
    protected function configure()
    {
        $this->setName('decode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input1 = 'H GgG d gGg e ggG l GGg o gGG r Ggg w ggg
GgGggGGGgGGggGG, ggggGGGggGGggGg!';

        $input2 = 'a GgG d GggGg e GggGG g GGGgg h GGGgG i GGGGg l GGGGG m ggg o GGg p Gggg r gG y ggG
GGGgGGGgGGggGGgGggG /gG/GggGgGgGGGGGgGGGGGggGGggggGGGgGGGgggGGgGggggggGggGGgG!';

        $input3 = 'C GgggGgg H GgggGgG T GgggGGg a gGg c GGggG d GggG e GgG g ggGgG h GGgGg i gGGg j GgggGGG l gGGG m ggGGg n GGgGG o ggg p ggGGG r GGGg s GGGG t GGgggG u ggGgg v Ggggg w GGggggg y GGggggG
GgggGGgGGgGggGGgGGGG GGggGGGgGggGggGGGgGGGGgGGGgGGggGgGGgG GGggggggGgGGGG ggGGGGGGggggggGGGgggGGGGGgGGggG gGgGGgGGGggG GggGgGGgGGGGGGggGggGggGGGGGGGGGgGGggG gggGggggGgGGGGg gGgGGgggG /GGGg/GggGgGggGGggGGGGGggggGggGGGGGGggggggGgGGGGggGgggGGgggGGgGgGGGGg_gGGgGggGGgGgGgGGGG. GgggGgGgGgGggggGgG gGg GGggGgggggggGGG GGggGGGgGggGggGGGgGGGGgGGGgGGggGgGGgG gGGgGggGGgGgGg? GgggGgggggggGGgGgG GgggGGGggggGGgGGgGG ggGggGGGG gggGggggGgGGGGg GGgggGGGgGgGgGGGGgGgG!';

        $lines = explode("\n", $input3);
        $words = explode(' ', $lines[0]);

        for ($i = 0; $i < count($words); $i = $i + 2) {
            $values[] = $words[$i];
            $keys[] = $words[$i + 1];
        }

        unset($lines[0]);

        $decoded = '';
        foreach ($lines as $line) {
            $tmp = '';
            for ($i = 0; $i < strlen($line); $i++) {
                if (preg_match('/[a-zA-Z]/i', $line[$i])) {
                    $tmp = $tmp . $line[$i];
                    $search = array_search($tmp, $keys);
                    if ($search !== false) {
                        $decoded .= $values[$search];
                        $tmp = '';
                    }
                } else {
                    $decoded .= $line[$i];
                }
            }
        }

        $output->writeLn($decoded);
    }
}
