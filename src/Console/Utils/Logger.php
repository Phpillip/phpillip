<?php


namespace Phpillip\Console\Utils;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console Logger
 */
class Logger
{
    /**
     * Output
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Progress Bar
     *
     * @var ProgressBar
     */
    protected $progress;

    /**
     * Logs
     *
     * @var array
     */
    protected $logs;

    /**
     * Constructor
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
        $this->logs   = [];
    }

    /**
     * Log
     *
     * @param string $message
     */
    public function log($message)
    {
        if ($this->progress) {
            $this->progress->setMessage($message);
            $this->logs[] = $message;
        } else {
            $this->output->writeLn($message);
        }
    }

    /**
     * Get progress bar instance
     *
     * @param integer $total
     *
     * @return ProgressBar
     */
    public function getProgress($total = null)
    {
        if (!$this->progress || $this->progress->getMaxSteps() === $this->progress->getProgress()) {
            $this->progress = new ProgressBar($this->output, $total);
        }

        return $this->progress;
    }

    /**
     * Start progress
     */
    public function start()
    {
        if ($this->progress){
            $this->progress->start();
        }
    }

    /**
     * Advance progress
     */
    public function advance()
    {
        if ($this->progress){
            $this->progress->advance();
        }
    }

    /**
     * Finish progress
     */
    public function finish()
    {
        if ($this->progress){
            $this->progress->finish();
            $this->progress = null;
            $this->flush();
        }
    }

    /**
     * Flush message queue
     */
    public function flush()
    {
        if (!$this->progress) {
            $this->log('');

            foreach ($this->logs as $message) {
                $this->log($message);
            }

            $this->logs = [];
        }
    }
}
