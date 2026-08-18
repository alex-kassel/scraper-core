<?php

namespace AlexKassel\ScraperCore\Tests\Unit;

use PHPUnit\Framework\TestCase;
use AlexKassel\ScraperCore\Lifecycle\FingerprintComparator;

class FingerprintComparatorTest extends TestCase
{
    private FingerprintComparator $comparator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->comparator = new FingerprintComparator();
    }

    public function test_calculate_fingerprint_returns_null_for_null_payload(): void
    {
        $this->assertNull($this->comparator->calculateFingerprint(null));
    }

    public function test_calculate_fingerprint_returns_deterministic_md5_string(): void
    {
        $payload1 = ['b' => 2, 'a' => 1];
        $payload2 = ['a' => 1, 'b' => 2];

        $fp1 = $this->comparator->calculateFingerprint($payload1);
        $fp2 = $this->comparator->calculateFingerprint($payload2);

        $this->assertNotNull($fp1);
        $this->assertEquals(32, strlen($fp1));
        $this->assertEquals($fp1, $fp2);
    }

    public function test_matches_returns_true_for_identical_fingerprints(): void
    {
        $fp = md5('test');
        $this->assertTrue($this->comparator->matches($fp, $fp));
    }

    public function test_matches_returns_false_when_either_fingerprint_is_null(): void
    {
        $fp = md5('test');
        $this->assertFalse($this->comparator->matches(null, $fp));
        $this->assertFalse($this->comparator->matches($fp, null));
    }
}
