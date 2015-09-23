<?php

namespace Phpillip\Console\Command;

use Exception;
use Phpillip\Console\EventListener\SitemapListener;
use Phpillip\Console\Model\Builder;
use Phpillip\Console\Model\Logger;
use Phpillip\Console\Model\Sitemap;
use Phpillip\Model\Paginator;
use Phpillip\Routing\Route;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build Command
 */
class BuildCommand extends Command
{
    /**
     * Phpillip Application
     *
     * @var Application
     */
    protected $app;

    /**
     * Console logger
     *
     * @var Logger
     */
    protected $logger;

    /**
     * Static site builder
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Sitemap (optional)
     *
     * @var Sitemap
     */
    protected $sitemap;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('phpillip:build')
            ->setDescription('Build static website')
            ->addArgument(
                'host',
                InputArgument::OPTIONAL,
                'What should be used as domain name for absolute url generation?'
            )
            ->addArgument(
                'destination',
                InputArgument::OPTIONAL,
                'Full path to destination directory'
            )
            ->addOption(
                'no-sitemap',
                null,
                InputOption::VALUE_NONE,
                'Don\'t build the sitemap'
            )
            ->addOption(
                'no-expose',
                null,
                InputOption::VALUE_NONE,
                'Don\'t expose the public directory after build'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->app    = $this->getApplication()->getKernel();
        $this->logger = new Logger($output);

        $destination = $input->getArgument('destination') ?: $this->app['root'] . $this->app['dst_path'];

        $this->builder = new Builder($this->app, $destination);

        if (!$input->getOption('no-sitemap')) {
            $this->sitemap = new Sitemap();
            $this->app['dispatcher']->addSubscriber(new SitemapListener($this->app['routes'], $this->sitemap));
        }

        if ($host = $input->getArgument('host')) {
            $this->app['url_generator']->getContext()->setHost($host);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->logger->log('Clearing destination folder...');

        $this->builder->clear();

        $this->logger->log(sprintf('Building <info>%s</info> routes...', $this->app['routes']->count()));

        foreach ($this->app['routes'] as $name => $route) {
            $this->dump($route, $name);
        }

        if ($this->sitemap) {
            $this->buildSitemap($this->sitemap);
        }

        if (!$input->getOption('no-expose') && $this->getApplication()->has('phpillip:expose')) {
            $arguments = [
                'command'     => 'phpillip:expose',
                'destination' => $input->getArgument('destination'),
            ];
            $this->getApplication()->get('phpillip:expose')->run(new ArrayInput($arguments), $output);
        }
    }

    /**
     * Dump route content to destination file
     *
     * @param Route $route
     * @param string $name
     */
    protected function dump(Route $route, $name)
    {
        if (!$route->isVisible()) {
            return;
        }

        if (!in_array('GET', $route->getMethods())) {
            throw new Exception(sprintf('Only GET mehtod supported, "%s" given.', $name));
        }

        if ($route->hasContent()) {
            if ($route->isList()) {
                if ($route->isPaginated()) {
                    $this->buildPaginatedRoute($route, $name);
                } else {
                    $this->buildListRoute($route, $name);
                }
            } else {
                $this->buildContentRoute($route, $name);
            }
        } else {
            $this->logger->log(sprintf('Building route <comment>%s</comment>', $name));
            $this->builder->build($route, $name);
        }
    }

    /**
     * Build paginated route
     *
     * @param Route $route
     * @param string $name
     */
    protected function buildPaginatedRoute(Route $route, $name)
    {
        $contentType = $route->getContent();
        $contents    = $this->app['content_repository']->listContents($contentType);
        $paginator   = new Paginator($contents, $route->getPerPage());

        $this->logger->log(sprintf(
            'Building route <comment>%s</comment> for <info>%s</info> pages',
            $name,
            $paginator->count()
        ));
        $this->logger->getProgress($paginator->count());
        $this->logger->start();

        foreach ($paginator as $index => $contents) {
            $this->builder->build($route, $name, ['page' => $index + 1]);
            $this->logger->advance();
        }

        $this->logger->finish();
    }

    /**
     * Build list route
     *
     * @param Route $route
     * @param string $name
     */
    protected function buildListRoute(Route $route, $name)
    {
        $contentType = $route->getContent();
        $contents    = $this->app['content_repository']->listContents($contentType);

        $this->logger->log(sprintf(
            'Building route <comment>%s</comment> with <info>%s</info> <comment>%s(s)</comment>',
            $name,
            count($contents),
            $contentType
        ));
        $this->builder->build($route, $name);
    }

    /**
     * Build content route
     *
     * @param Route $route
     * @param string $name
     */
    protected function buildContentRoute(Route $route, $name)
    {
        $contentType = $route->getContent();
        $contents    = $this->app['content_repository']->listContents($contentType);

        $this->logger->log(sprintf(
            'Building route <comment>%s</comment> for <info>%s</info> <comment>%s(s)</comment>',
            $name,
            count($contents),
            $route->getContent()
        ));
        $this->logger->getProgress(count($contents));
        $this->logger->start();

        foreach ($contents as $content) {
            $this->builder->build($route, $name, [$contentType => $content]);
            $this->logger->advance();
        }

        $this->logger->finish();
    }

    /**
     * Build sitemap xml file from Sitemap
     *
     * @param Sitemap $sitemap
     */
    protected function buildSitemap(Sitemap $sitemap)
    {
        $this->logger->log(sprintf('Building sitemap with <comment>%s</comment> urls.', count($sitemap)));

        $content = $this->app['twig']->render('@phpillip/sitemap.xml.twig', ['sitemap' => $sitemap]);

        $this->builder->write('/', $content, 'xml', 'sitemap');
    }
}
