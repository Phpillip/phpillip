<?php

namespace Phpillip\Console\Command;

use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Watch for changes in the sources to re-run the build
 */
class WatchCommand extends Command
{
    /**
     * Command to execute on hit
     *
     * @var Command
     */
    protected $command;

    /**
     * Input for the command
     *
     * @var Input
     */
    protected $input;

    /**
     * Last build date
     *
     * @var DateTime
     */
    protected $lastBuild;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('phpillip:watch')
            ->setDescription('Watch for changes in the sources to re-run the build')
            ->addOption('period', null, InputOption::VALUE_REQUIRED, 'Set the polling period in seconds', 1)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->command = $this->getApplication()->get('phpillip:build');
        $this->input   = new ArrayInput(['command' => $this->command->getName()]);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $period = $input->getOption('period');
        $source = $this->getApplication()->getKernel()['root'];

        $this->build($output);

        $output->writeln(sprintf('[ Watching for changes in <comment>%s</comment> ]', $source));

        while (true) {
            $finder = new Finder();

            if ($finder->in($source)->date(sprintf('since %s', $this->lastBuild->format('c')))->count()) {
                $this->build($output);
            }

            sleep($period);
        }
    }

    /**
     * Run the build command
     *
     * @param OutputInterface $output
     */
    protected function build(OutputInterface $output)
    {
        $this->command->run($this->input, $output);

        $this->lastBuild = new DateTime();
    }
}
