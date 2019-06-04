<?php

namespace SamuelDavis\Proc;

use Exception;
use IteratorAggregate;

class Proc implements IteratorAggregate
{
    /** @var string */
    private $cmd;
    /** @var bool|resource */
    private $process;
    /** @var bool[]|resource[] */
    private $pipes = [];

    public function __construct(string $cmd)
    {
        $this->cmd = $cmd;
    }

    public function __invoke(array $spec = [], string $dir = null, array $env = null): Proc
    {
        $this->__destruct();
        $spec += [['pipe', 'r'], ['pipe', 'w'], STDERR];
        $dir = $dir ?? getcwd();
        $env = $env ?? getenv();
        $this->process = proc_open($this, $spec, $this->pipes, $dir, $env);
        if (!is_resource($this->process)) {
            throw new Exception("Could not open process: `{$this}`");
        }
        return $this;
    }

    public function __destruct()
    {
        if (is_resource($this->process)) {
            $signal = SIGKILL;
            proc_terminate($this->process, $signal);
        }

        foreach ($this->pipes as $pipe) {
            if (is_resource($pipe)) {
                fclose($pipe);
            }
        }
        if (is_resource($this->process) && ($code = proc_close($this->process)) === false) {
            throw new Exception("Could not close process: `{$this->getStatus('pid')}`");
        }
    }

    private function getStatus(string $key = null)
    {
        if (!is_resource($this->process)) {
            return null;
        }
        $status = proc_get_status($this->process);
        return $key === null ? $status : $status[$key] ?? null;
    }

    public function isRunning(): bool
    {
        return $this->getStatus('running') === true;
    }

    public function getIterator()
    {
        while (is_resource($this->pipes[1]) && ($line = fgets($this->pipes[1]))) {
            yield rtrim($line, PHP_EOL);
        }
    }

    public function write(string $buffer): Proc
    {
        if (is_resource($this->pipes[0])) {
            fwrite($this->pipes[0], $buffer);
        }
        return $this;
    }

    public function __toString()
    {
        return $this->cmd;
    }
}
