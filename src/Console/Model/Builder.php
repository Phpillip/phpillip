<?php

namespace Phpillip\Console\Model;

use Phpillip\Routing\Route;
use Silex\Application;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Static site builder
 */
class Builder
{
    /**
     * Application
     *
     * @var Application
     */
    protected $app;

    /**
     * Path to build route to
     *
     * @var string
     */
    protected $destination;

    /**
     * File system
     *
     * @var FileSystem
     */
    protected $files;

    /**
     * Constructor
     *
     * @param Application $app
     * @param string $destination
     */
    public function __construct(Application $app, $destination)
    {
        $this->app         = $app;
        $this->destination = $destination;
        $this->files       = new Filesystem();
    }

    /**
     * Clear destination folder
     */
    public function clear()
    {
        if ($this->files->exists($this->destination)) {
            $this->files->remove($this->destination);
        }

        $this->files->mkdir($this->destination);
    }

    /**
     * Dump the given Route into a file
     *
     * @param Route $route
     * @param string $name
     * @param array $parameters
     */
    public function build(Route $route, $name, array $parameters = [])
    {
        $url      = $this->app['url_generator']->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
        $request  = Request::create($url, 'GET', array_merge(['_format' => $route->getFormat()], $parameters));
        $response = $this->app->handle($request);$

        $this->write(
            $this->getFilePath($route, $parameters),
            $response->getContent(),
            $request->getFormat($response->headers->get('Content-Type')),
            $route->getFileName()
        );
    }

    /**
     * Write a file
     *
     * @param string $path The directory to put the file in (in the current destination)
     * @param string $content The file content
     * @param string $filename The file name
     * @param string $extension The file extension
     */
    public function write($path, $content, $extension = 'html', $filename = 'index')
    {
        $directory = sprintf('%s/%s', $this->destination, trim($path, '/'));
        $file      = sprintf('%s.%s', $filename, $extension);

        if (!$this->files->exists($directory)) {
            $this->files->mkdir($directory);
        }

        $this->files->dumpFile(sprintf('%s/%s', $directory, $file), $content);
    }

    /**
     * Get destination file path for the given route / parameters
     *
     * @param Route $route
     * @param array $parameters
     *
     * @return string
     */
    protected function getFilePath(Route $route, array $parameters = [])
    {
        $filepath = trim($route->getFilePath(), '/');

        foreach ($route->getDefaults() as $key => $value) {
            if (isset($parameters[$key]) && $parameters[$key] == $value) {
                $filepath = rtrim(preg_replace(sprintf('#{%s}/?#', $key), null, $filepath), '/');
            }
        }

        foreach ($parameters as $key => $value) {
            $filepath = str_replace(sprintf('{%s}', $key), (string) $value, $filepath);
        }

        return $filepath;
    }
}
