<?php

namespace AlexKassel\ScraperCore\Tests\Unit;

use PHPUnit\Framework\TestCase;
use AlexKassel\ScraperCore\Lifecycle\UnchangedBatchBuffer;

class UnchangedBatchBufferTest extends TestCase
{
    public function test_buffer_flushes_when_threshold_reached(): void
    {
        $flushed = [];
        $buffer = new UnchangedBatchBuffer(threshold: 3, flushCallback: function (array $items) use (&$flushed) {
            $flushed = $items;
        });

        $buffer->add('item-1');
        $buffer->add('item-2');
        $this->assertEquals(0, count($flushed));

        $buffer->add('item-3');
        $this->assertEquals(['item-1', 'item-2', 'item-3'], $flushed);
        $this->assertEquals(0, $buffer->count());
    }

    public function test_manual_flush_empties_buffer(): void
    {
        $flushed = [];
        $buffer = new UnchangedBatchBuffer(threshold: 100, flushCallback: function (array $items) use (&$flushed) {
            $flushed = $items;
        });

        $buffer->add('item-1');
        $buffer->add('item-2');

        $count = $buffer->flush();
        $this->assertEquals(2, $count);
        $this->assertEquals(['item-1', 'item-2'], $flushed);
        $this->assertEquals(0, $buffer->count());
    }
}
