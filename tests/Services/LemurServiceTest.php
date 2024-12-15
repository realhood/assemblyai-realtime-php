<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use AssemblyAIRealtime\Services\LemurService;
use AssemblyAIRealtime\Exceptions\LemurException;

class LemurServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        $this->service = new LemurService([]);
    }

    public function testProcessLemurRequest()
    {
        $text = 'Test transcription';
        $task = 'summarize';
        $prompt = 'Provide a summary';

        $result = $this->service->processLemurRequest($text, $task, $prompt);

        $this->assertArrayHasKey('type', $result);
        $this->assertArrayHasKey('task', $result);
        $this->assertArrayHasKey('response', $result);
        $this->assertArrayHasKey('confidence', $result);
        $this->assertEquals('lemur', $result['type']);
    }

    public function testProcessLemurRequestWithInvalidTask()
    {
        $this->expectException(LemurException::class);
        $this->service->processLemurRequest('test', 'invalid_task', 'test prompt');
    }
}