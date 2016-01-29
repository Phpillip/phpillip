<?php

namespace Phpillip;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * Phpillip Kernel
 */
abstract class PhpillipKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
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
    }

    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        // load bundles' configuration
        $container->loadFromExtension('framework', [
            'secret'     => '12345',
            'profiler'   => null,
            'templating' => ['engines' => ['twig']],
        ]);

        $container->loadFromExtension('web_profiler', ['toolbar' => true]);

        // add configuration parameters
        $container->setParameter('mail_sender', 'user@example.com');

        // register services
        $container->register('app.markdown', 'AppBundle\\Service\\Parser\\Markdown');
    }
}
