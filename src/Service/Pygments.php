<?php

namespace Phpillip\Service;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Pygments code highlight
 */
class Pygments
{
    /**
     * File system
     *
     * @var FileSystem
     */
    protected $files;

    /**
     * Temporary directory path
     *
     * @var string
     */
    protected $tmp;

    /**
     * Constructor
     *
     * @param string $tmp
     */
    public function __construct($tmp = null)
    {
        $this->tmp   = $tmp ?: sys_get_temp_dir();
        $this->files = new Filesystem();
    }

    /**
     * Highlight a portion of code with pygmentize
     *
     * @param string $value
     * @param string $language
     *
     * @return string
     */
    public function highlight($value, $language)
    {
        $path = tempnam($this->tmp, 'pyg');

        if ($language === 'php' && substr($value, 0, 5) !== '<?php') {
            $value = '<?php ' . PHP_EOL . $value;
        }

        $this->files->dumpFile($path, $value);

        $value = $this->pygmentize($path, $language);

        unlink($path);

        if (preg_match('#^<div class="highlight"><pre>#', $value) && preg_match('#</pre></div>$#', $value)) {
            return substr($value, 28, strlen($value) - 40);
        }

        return $value;
    }

    /**
     * Run 'pygmentize' command on the given file
     *
     * @param string $path
     * @param string $language
     *
     * @return string
     */
    public function pygmentize($path, $language)
    {
        $process = new Process(sprintf('pygmentize -f html -l %s %s', $language, $path));

        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return trim($process->getOutput());
    }

    /**
     * Is pygmentize available?
     *
     * @return boolean
     */
    public static function isAvailable()
    {
        $process = new Process('pygmentize -V');

        $process->run();

        return $process->isSuccessful();
    }
}
