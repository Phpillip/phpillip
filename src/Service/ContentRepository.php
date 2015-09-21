<?php

namespace Phpillip\Service;

use DateTime;
use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Phpillip\Behavior\PropertyHandlerInterface;

/**
 * Content repository
 */
class ContentRepository
{
    /**
     * Content Decoder
     *
     * @var DecoderInterface
     */
    protected $decoder;

    /**
     * Finder
     *
     * @var Finder
     */
    protected $finder;

    /**
     * File browser
     *
     * @var FileSystem
     */
    protected $files;

    /**
     * Property handlers
     *
     * @var array
     */
    protected $handlers;

    /**
     * Contents root directory
     *
     * @var string
     */
    protected $directory;

    /**
     * Cache
     *
     * @var array
     */
    protected $cache;

    /**
     * Constructor
     *
     * @param DecoderInterface $decoder The decoder that will parse content files
     * @param string $contentDir Path to content files
     */
    public function __construct(DecoderInterface $decoder, $directory)
    {
        $this->decoder   = $decoder;
        $this->directory = rtrim($directory, '/');
        $this->files     = new FileSystem();
        $this->handlers  = [];
        $this->cache     = [
            'files'    => [],
            'contents' => [],
        ];
    }

    /**
     * Get all contents for the given type
     *
     * @param string $type Type of content to load
     * @param string|null $index Index the result array by the given field name (from content)
     * @param boolean|null $order Sort the result array: true for ascending, false for descending
     *
     * @return array[] Array of contents
     */
    public function getContents($type, $index = null, $order = true)
    {
        $contents = [];
        $files    = $this->listFiles($type);

        foreach ($files as $file) {
            $content = $this->load($file);
            $contents[$this->getIndex($file, $content, $index)] = $content;
        }

        if ($order !== null) {
            $order ? ksort($contents) : krsort($contents);
        }

        return $contents;
    }

    /**
     * List of content names for the given type
     *
     * @param string $type Type of content to list
     *
     * @return string[] Content names
     */
    public function listContents($type)
    {
        $names = [];
        $files = $this->listFiles($type);

        foreach ($files as $file) {
            $names[] = static::getName($file);
        }

        return $names;
    }

    /**
     * Get the content for the given type and name
     *
     * @param string $type Type of content to load
     * @param string $name The name of the content file (without extension)
     *
     * @return array Content as associative array
     */
    public function getContent($type, $name)
    {
        $finder = $this->listFiles($type)->name($name . '.*');

        if (!$finder->count()) {
            throw new Exception(sprintf('No content directory find for type "%s" and name "%s" (in "%s").', $type, $name, $this->directory), 1);
        }

        foreach ($finder as $file) {
            return $this->load($file);
        }

        return null;
    }

    /**
     * Add property handler
     *
     * @param PropertyHandlerInterface $handler Handler
     *
     * @return ContentRepository
     */
    public function addPropertyHandler(PropertyHandlerInterface $handler)
    {
        $this->handlers[$handler->getProperty()] = $handler;

        return $this;
    }

    /**
     * Get the name of a file
     *
     * @param SplFileInfo $file The file
     *
     * @return string The name
     */
    public static function getName(SplFileInfo $file)
    {
        $name = $file->getRelativePathname();

        return substr($name, 0, strrpos($name, '.'));
    }

    /**
     * Get the format of a file from its extension
     *
     * @param SplFileInfo $file The file
     *
     * @return string The format
     */
    public static function getFormat(SplFileInfo $file)
    {
        $name = $file->getRelativePathname();
        $ext  = substr($name, strrpos($name, '.') + 1);

        switch ($ext) {
            case 'md':
                return 'markdown';

            case 'yml':
                return 'yaml';

            default:
                return $ext;
        }
    }

    /**
     * List files for the given type
     *
     * @param string $type Type of content to list
     *
     * @return Finder A Finder instance, filtered by type
     */
    protected function listFiles($type)
    {
        if (!isset($this->cache['files'][$type])) {
            $path = sprintf('%s/%s', $this->directory, $type);

            if (!$this->files->exists($path)) {
                throw new Exception(sprintf('No content directory found for type "%s" (in "%s").', $type, $this->directory), 1);
            }

            $finder = new Finder();

            $this->cache['files'][$type] = $finder->files()->in($path);
        }

        return clone $this->cache['files'][$type];
    }

    /**
     * Get index of the given content for content lists
     *
     * @param SplFileInfo $file
     * @param array $content
     * @param string|null $key
     *
     * @return string The string index (by default, the file name)
     */
    protected function getIndex(SplFileInfo $file, $content, $key = null)
    {
        if ($key === null || !isset($content[$key])) {
            return static::getName($file);
        }

        $index = $content[$key];

        if ($index instanceof DateTime) {
            return $index->format('U');
        }

        return (string) $index;
    }

    /**
     * Get the file content
     *
     * @param SplFileInfo $file The file to load
     *
     * @return array Parsed content (associative array)
     */
    protected function load(SplFileInfo $file)
    {
        $path = $file->getPathName();

        if (!isset($this->cache['contents'][$path])) {
            $data    = $this->decoder->decode($file->getContents(), static::getFormat($file));
            $context = ['file' => $file];

            if (is_array($data)) {
                foreach ($this->handlers as $property => $handler) {
                    if ($handler->isSupported($data)) {
                        $data[$property] = $handler->handle(isset($data[$property]) ? $data[$property] : null, $context);
                    }
                }
            }

            $this->cache['contents'][$path] = $data;
        }

        return $this->cache['contents'][$path];
    }
}
