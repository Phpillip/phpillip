<?php

namespace Phpillip\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Runs Phpillip application using PHP built-in web server.
 *
 * Inspired by Symfony's ServerRundCommand:
 * https://github.com/symfony/FrameworkBundle/blob/master/Command/ServerRunCommand.php
 *
 * @author Michał Pipa <michal.pipa.xsolve@gmail.com>
 */
class ServeCommand extends Command
{
    /**
     * Application
     *
     * @var Phpillip\Application
     */
    protected $app;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->app = $this->getApplication()->getKernel();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('phpillip:serve')
            ->setDescription('Runs your Phpillip application with PHP built-in web server')
            ->setDefinition([
                new InputArgument('address', InputArgument::OPTIONAL, 'Address:port', '127.0.0.1'),
                new InputOption('port', 'p', InputOption::VALUE_REQUIRED, 'Address port number', '8000'),
            ])
            ->setHelp(<<<EOF
The <info>%command.name%</info> runs PHP built-in web server:

  <info>%command.full_name%</info>

To change default bind address and port use the <info>address</info> argument:

  <info>%command.full_name% 127.0.0.1:8080</info>

See also: http://www.php.net/manual/en/features.commandline.webserver.php

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $documentRoot = $this->app['root'] . $this->app['public_path'];

        if (!is_dir($documentRoot)) {
            $output->writeln(sprintf('<error>The document root directory "%s" does not exist</error>', $documentRoot));

            return 1;
        }

        $address = $input->getArgument('address');

        if (false === strpos($address, ':')) {
            $address = $address.':'.$input->getOption('port');
        }

        if ($this->isOtherServerProcessRunning($address)) {
            $output->writeln(sprintf('<error>A process is already listening on http://%s.</error>', $address));

            return 1;
        }

        $output->writeln(sprintf("Server running on <info>http://%s</info>\n", $address));
        $output->writeln('Quit the server with CONTROL-C.');

        if (null === $builder = $this->createPhpProcessBuilder($output, $address)) {
            return 1;
        }

        $builder->setWorkingDirectory($documentRoot);
        $builder->setTimeout(null);
        $process = $builder->getProcess();

        if (OutputInterface::VERBOSITY_VERBOSE > $output->getVerbosity()) {
            $process->disableOutput();
        }

        $this
            ->getHelper('process')
            ->run($output, $process, null, null, OutputInterface::VERBOSITY_VERBOSE);

        if (!$process->isSuccessful()) {
            $output->writeln('<error>Built-in server terminated unexpectedly</error>');

            if ($process->isOutputDisabled()) {
                $output->writeln('<error>Run the command again with -v option for more details</error>');
            }
        }

        return $process->getExitCode();
    }

    /**
     * Get a ProcessBuilder instance
     *
     * @param OutputInterface $output
     * @param string $address
     *
     * @return ProcessBuilder
     */
    private function createPhpProcessBuilder(OutputInterface $output, $address)
    {
        $router = realpath(__DIR__ . '/../../Resources/config/router.php');
        $finder = new PhpExecutableFinder();

        if (false === $binary = $finder->find()) {
            $output->writeln('<error>Unable to find PHP binary to run server</error>');

            return;
        }

        return new ProcessBuilder([$binary, '-S', $address, $router]);
    }

    /**
     * Is another service already using the given address?
     *
     * @param string $address
     *
     * @return boolean
     */
    protected function isOtherServerProcessRunning($address)
    {
        if (file_exists(sys_get_temp_dir().'/'.strtr($address, '.:', '--').'.pid')) {
            return true;
        }

        list($hostname, $port) = explode(':', $address);

        if (false !== $fp = @fsockopen($hostname, $port, $errno, $errstr, 5)) {
            fclose($fp);

            return true;
        }

        return false;
    }
}
