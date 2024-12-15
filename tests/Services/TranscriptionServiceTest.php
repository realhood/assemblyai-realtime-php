<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use AssemblyAIRealtime\Services\TranscriptionService;
use AssemblyAIRealtime\Exceptions\TranscriptionException;

class TranscriptionServiceTest extends TestCase
{
    private $websocketMock;
    private $service;

    protected function setUp(): void
    {
        $this->websocketMock = $this->createMock(\Ratchet\Client\WebSocket::class);
        $this->service = new TranscriptionService($this->websocketMock, []);
    }

    public function testHandleTranscriptionResult()
    {
        $result = [
            'message_type' => 'FinalTranscript',
            'text' => 'Test transcription',
            'confidence' => 0.95,
            'words' => [['word' => 'test', 'confidence' => 0.95]],
            'timestamp' => time()
        ];

        $processed = $this->service->handleTranscriptionResult($result);

        $this->assertEquals('final', $processed['type']);
        $this->assertEquals($result['text'], $processed['text']);
        $this->assertEquals($result['confidence'], $processed['confidence']);
        $this->assertEquals($result['words'], $processed['words']);
    }

    public function testProcessAudioStreamWithInvalidFormat()
    {
        $this->expectException(TranscriptionException::class);
        $this->service->processAudioStream('test.txt');
    }
}