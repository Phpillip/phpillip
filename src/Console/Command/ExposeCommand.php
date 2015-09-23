<?php

namespace Phpillip\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
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
            ->addArgument('destination', InputArgument::OPTIONAL, 'Full path to destination directory')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Exposing public path.</comment>');

        $app         = $this->getApplication()->getKernel();
        $source      = $app['root'] . $app['public_path'];
        $destination = $input->getArgument('destination') ?: $app['root'] . $app['dst_path'];
        $finder      = new Finder();
        $files       = new Filesystem();

        foreach ($finder->files()->in($source) as $file) {
            $files->copy(
                $file->getPathName(),
                str_replace($source, $destination, $file->getPathName()),
                true
            );
        }
    }
}
