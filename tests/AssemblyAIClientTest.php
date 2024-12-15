<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use AssemblyAIRealtime\Client\AssemblyAIClient;
use AssemblyAIRealtime\Config\Configuration;
use AssemblyAIRealtime\Exceptions\AssemblyAIException;

class AssemblyAIClientTest extends TestCase
{
    private $apiKey = 'test-api-key';
    private $client;

    protected function setUp(): void
    {
        $this->client = new AssemblyAIClient($this->apiKey);
    }

    public function testClientInitialization()
    {
        $this->assertInstanceOf(AssemblyAIClient::class, $this->client);
    }

    public function testConfigurationWithLemur()
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

    public function testInvalidApiKey()
    {
        $this->expectException(AssemblyAIException::class);
        $client = new AssemblyAIClient('');
    }
}