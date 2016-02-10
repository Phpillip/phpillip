<?php

namespace Phpillip\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ServerRunCommand;

/**
 * Runs Phpillip application using PHP built-in web server.
 * Based on Symfony's ServerRunCommand.
 */
class ServeCommand extends ServerRunCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('phpillip:serve');
        $this->setDescription('Runs your Phpillip application with PHP built-in web server');
        $this->getDefinition()->getOption('router')->setDefault(realpath(__DIR__ . '/../../Resources/bin/router.php'));
    }
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getDefinition()->getOption('docroot')->setDefault(
            $this->getContainer()->getParameter('kernel.root_dir').'/../dist'
        );

        parent::execute($input, $output);
    }
}
