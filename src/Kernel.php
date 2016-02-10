<?php

namespace Phpillip;

use Phpillip\Routing\RouteCollectionBuilder;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Phpillip Kernel
 */
abstract class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new \
            Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            //new SilexProvider\HttpFragmentServiceProvider(),
            //new SilexProvider\UrlGeneratorServiceProvider(),
            //new SilexProvider\ServiceControllerServiceProvider(),
            //new PhpillipProvider\InformatorServiceProvider(),
            //new PhpillipProvider\PygmentsServiceProvider(),
            //new PhpillipProvider\ParsedownServiceProvider(),
            //new PhpillipProvider\DecoderServiceProvider(),
            //new PhpillipProvider\ContentServiceProvider(),
            //new PhpillipProvider\TwigServiceProvider(),
            //new PhpillipProvider\SubscriberServiceProvider(),
            //new PhpillipProvider\ContentControllerServiceProvider(),
        ];

        if ($this->getEnvironment() == 'dev') {
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        // load bundles' configuration
        $container->loadFromExtension('framework', [
            'secret'     => md5('phpillip'),
            'templating' => ['engines' => ['twig']],
        ]);
        //$container->loadFromExtension('phpillip', []);

        $container->loadFromExtension('web_profiler', ['toolbar' => true]);

        // add configuration parameters
        $container->setParameter('root', $this->getRootDir());

        // register services
        $container->register('phpillip.markdown', 'AppBundle\\Service\\Parser\\Markdown');

        $container
            ->register('phpillip.template_listener', 'Phpillip\EventListener\TemplateListener')
            ->setArguments([new Reference('router'), new Reference('templating')])
            ->addTag('kernel.event_subscriber');

        $container
            ->register('phpillip.template_listener', 'Phpillip\EventListener\TemplateListener')
            ->setArguments([new Reference('router'), new Reference('templating')])
            ->addTag('kernel.event_subscriber');


        $container
            ->register('phpillip.twig_extension.public', 'Phpillip\Twig\PublicExtension')
            ->addMethodCall('setRoot', [new Parameter('root')])
            ->setPublic(false)
            ->addTag('twig.extension');

        $app['informator'] = $app->share(function ($app) {
            return new $app['informator_class']($app['url_generator']);
        });

        $app->before([$app['informator'], 'beforeRequest']);

        /*$container
            ->register('phpillip.twig_extension.markdown', 'Phpillip\Twig\MarkdownExtension')
            ->addArgument(new Reference('phpillip.markdown'))
            ->setPublic(false)
            ->addTag('twig.extension');*/
    }

    /**
     * {@inheritdoc}
     */
    public function loadRoutes(LoaderInterface $loader)
    {
        $routes = new RouteCollectionBuilder($loader);
        $this->configureRoutes($routes);

        return $routes->build();
    }
}
