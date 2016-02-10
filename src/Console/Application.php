<?php

namespace Phpillip\Console;

use Phpillip\Console\Command;
use Symfony\Component\Console\Application as BaseApplication;
#use Symfony\Bundle\FrameworkBundle\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Application
 */
class Application extends BaseApplication
{
    /**
     * Kernel
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Command registered
     *
     * @var boolean
     */
    private $commandsRegistered = false;

    /**
     * {@inheritdoc}
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;

        //parent::__construct($kernel);

        //$this->setName('Phpillip');

        parent::__construct('Phpillip', $kernel::VERSION.' - '.$kernel->getName().'/'.$kernel->getEnvironment().($kernel->isDebug() ? '/debug' : ''));
        $this->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', $kernel->getEnvironment()));
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

        /*if (!$this->commandsRegistered) {
            $this->registerCommands();
            $this->commandsRegistered = true;
        }

        $container = $this->kernel->getContainer();

        foreach ($this->all() as $command) {
            if ($command instanceof ContainerAwareInterface) {
                $command->setContainer($container);
            }
        }

        $this->setDispatcher($container->get('event_dispatcher'));*/

        return parent::doRun($input, $output);
    }

    /*protected function registerCommands()
    {
        $container = $this->kernel->getContainer();

        foreach ($this->kernel->getBundles() as $bundle) {
            if ($bundle instanceof Bundle) {
                $bundle->registerCommands($this);
            }
        }

        if ($container->hasParameter('console.command.ids')) {
            foreach ($container->getParameter('console.command.ids') as $id) {
                $this->add($container->get($id));
            }
        }
    }*/

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            [
                new Command\BuildCommand(),
                new Command\ExposeCommand(),
                new Command\ServeCommand(),
                new Command\WatchCommand(),
            ]/*,
            array_map(function ($className) {
                return new $className;
            }, $this->getKernel()['commands'])*/
        );
    }
}
