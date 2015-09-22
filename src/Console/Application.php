<?php

namespace Phpillip\Console;

use Phpillip\Console\Command;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Application
 */
class Application extends BaseApplication
{
    /**
     * Kernel
     *
     * @var HttpKernelInterface
     */
    protected $kernel;

    /**
     * {@inheritdoc}
     */
    public function __construct(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;

        parent::__construct('Phpillip', $kernel::VERSION);

        $this->getDefinition()->addOption(new InputOption('--no-debug', null, InputOption::VALUE_NONE, 'Switches off debug mode.'));
    }

    /**
     * Gets the Kernel associated with this Console.
     *
     * @return KernelInterface A KernelInterface instance
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->kernel->boot();
        $this->kernel->flush();

        return parent::doRun($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            [
                new Command\BuildCommand(),
                new Command\ServeCommand(),
                new Command\ExposeCommand(),
            ]
        );
    }
}
