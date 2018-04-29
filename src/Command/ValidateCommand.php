<?php
declare(strict_types=1);

namespace Chgk\ChgkDb\Parser\Command;

use Chgk\ChgkDb\Parser\Iterator\FileLineIterator;
use Chgk\ChgkDb\Parser\Iterator\TextLineIterator;
use Chgk\ChgkDb\Parser\ParserFactory\ParserFactory;
use Chgk\ChgkDb\Parser\ParserFactory\UnregisteredParserException;
use Chgk\ChgkDb\Parser\TextParser\Exception\ParseException;
use Chgk\ChgkDb\Parser\TextParser\TextParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ValidateCommand extends Command
{
    protected function configure()
    {
        $this->setName('verify');
        $this->setDescription('Check if questions file is correct');
        $this->addArgument('file_name', InputArgument::REQUIRED);
        $this->addOption('encoding', 'e', InputOption::VALUE_OPTIONAL, 'Encoding', 'utf-8');
        $this->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Input format', TextParser::FORMAT_KEY);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws ValidateCommandException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $iterator = new FileLineIterator($input->getArgument('file_name'), $input->getOption('encoding'));
        $format = $input->getOption('format');
        $parserFactory = new ParserFactory();

        try {
            $parser = $parserFactory->getParser($format);
        } catch (UnregisteredParserException $e) {
            throw new ValidateCommandException('Invalid value format: '.$format);
        }
        try {
            $parser->parse($iterator);
        } catch (ParseException $exception) {
            throw new ValidateCommandException($input->getArgument('file_name').': '.$exception->getMessage());
        }
        $output->writeln('<info>OK</info>');
    }
}
