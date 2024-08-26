<?php

namespace Flawlol\FacadeIdeHelper\Command;

use Flawlol\FacadeIdeHelper\Interface\ProcessInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class IdeHelperCommand extends Command
{
    protected static $defaultName = 'app:generate-ide-helper';
    protected static $defaultDescription = 'Generates facade helpers for IDE support.';

    /**
     * IdeHelperCommand constructor.
     *
     * @param ProcessInterface $process The process interface for generating facade helpers.
     */
    public function __construct(private ProcessInterface $process)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface  $input  The input interface.
     * @param OutputInterface $output The output interface.
     *
     * @return int The command exit status.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->process->__invoke();

        $output->writeln('Facade helpers file generated successfully!');

        return Command::SUCCESS;
    }
}
