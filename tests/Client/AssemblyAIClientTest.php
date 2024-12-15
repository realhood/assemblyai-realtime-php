<?php

namespace Tests\Client;

use PHPUnit\Framework\TestCase;
use AssemblyAIRealtime\Client\AssemblyAIClient;
use AssemblyAIRealtime\Config\Configuration;
use AssemblyAIRealtime\Exceptions\AssemblyAIException;

class AssemblyAIClientTest extends TestCase
{
    private $apiKey = 'test-api-key';
    private $client;
    private $config;

    protected function setUp(): void
    {
        $this->config = new Configuration([
            'lemur' => [
                'enabled' => true,
                'task' => 'summarize',
                'prompt' => 'Test prompt'
            ]
        ]);
        $this->client = new AssemblyAIClient($this->apiKey, $this->config);
    }

    public function testClientInitialization()
    {
        $this->assertInstanceOf(AssemblyAIClient::class, $this->client);
    }

    public function testInvalidApiKey()
    {
        $this->expectException(AssemblyAIException::class);
        $this->expectExceptionMessage('API key cannot be empty');
        new AssemblyAIClient('');
    }

    public function testSendAudioChunkWithoutConnection()
    {
        $this->expectException(AssemblyAIException::class);
        $this->expectExceptionMessage('No active connection');
        $this->client->sendAudioChunk('test data');
    }

    public function testClientWithValidConfiguration()
    {
        $config = new Configuration([
            'lemur' => [
                'enabled' => true,
                'task' => 'summarize'
            ]
        ]);
        
        $client = new AssemblyAIClient($this->apiKey, $config);
        $this->assertInstanceOf(AssemblyAIClient::class, $client);
    }
}