<?php

namespace Tests\Utils;

use PHPUnit\Framework\TestCase;
use AssemblyAIRealtime\Utils\AudioUtils;

class AudioUtilsTest extends TestCase
{
    private $testAudioPath;

    protected function setUp(): void
    {
        $this->testAudioPath = __DIR__ . '/../fixtures/test.wav';
        // Create a test audio file
        file_put_contents($this->testAudioPath, 'test audio content');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testAudioPath)) {
            unlink($this->testAudioPath);
        }
    }

    public function testConvertAudioToFormat()
    {
        $chunks = AudioUtils::convertAudioToFormat($this->testAudioPath);
        $this->assertIsArray($chunks);
        $this->assertNotEmpty($chunks);
    }

    public function testConvertAudioToFormatWithInvalidFile()
    {
        $this->expectException(\InvalidArgumentException::class);
        AudioUtils::convertAudioToFormat('nonexistent.wav');
    }

    public function testValidateAudioFormat()
    {
        $this->assertTrue(AudioUtils::validateAudioFormat('test.wav'));
        $this->assertTrue(AudioUtils::validateAudioFormat('test.mp3'));
        $this->assertTrue(AudioUtils::validateAudioFormat('test.m4a'));
        $this->assertFalse(AudioUtils::validateAudioFormat('test.txt'));
    }

    public function testGetAudioDuration()
    {
        $duration = AudioUtils::getAudioDuration($this->testAudioPath);
        $this->assertIsFloat($duration);
        $this->assertGreaterThan(0, $duration);
    }
}