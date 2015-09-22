<?php

namespace Phpillip\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Expose the public directory
 */
class ExposeCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('phpillip:expose')
            ->setDescription('Expose the public directory')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getApplication()->getKernel();

        $this->source      = $app['root'] . $app['public_path'];
        $this->destination = $app['root'] . $app['dst_path'];
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Exposing public path.</comment>');

        $finder = new Finder();

        foreach ($finder->files()->in($this->source) as $file) {
            $this->files->copy(
                $file->getPathName(),
                str_replace($path, $this->destination, $file->getPathName()),
                true
            );
        }
    }
}
