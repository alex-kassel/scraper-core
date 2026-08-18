<?php

namespace AlexKassel\ScraperCore\Lifecycle;

use Closure;

class UnchangedBatchBuffer
{
    private array $buffer = [];

    public function __construct(
        private int $threshold = 500,
        private ?Closure $flushCallback = null
    ) {}

    public function setFlushCallback(Closure $callback): void
    {
        $this->flushCallback = $callback;
    }

    public function add(string $externalId): void
    {
        $this->buffer[] = $externalId;

        if (count($this->buffer) >= $this->threshold) {
            $this->flush();
        }
    }

    public function flush(): int
    {
        $count = count($this->buffer);
        if ($count === 0) {
            return 0;
        }

        $itemsToFlush = $this->buffer;
        $this->buffer = [];

        if ($this->flushCallback !== null) {
            ($this->flushCallback)($itemsToFlush);
        }

        return $count;
    }

    public function count(): int
    {
        return count($this->buffer);
    }
}
