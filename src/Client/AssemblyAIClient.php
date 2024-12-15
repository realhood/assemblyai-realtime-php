<?php

namespace AssemblyAIRealtime\Client;

use GuzzleHttp\Client as HttpClient;
use Ratchet\Client\WebSocket;
use React\EventLoop\LoopInterface;
use AssemblyAIRealtime\Config\Configuration;
use AssemblyAIRealtime\Handlers\MessageHandler;
use AssemblyAIRealtime\Exceptions\AssemblyAIException;

class AssemblyAIClient
{
    private string $apiKey;
    private Configuration $config;
    private HttpClient $httpClient;
    private LoopInterface $loop;
    private ?WebSocket $connection = null;
    private MessageHandler $messageHandler;

    public function __construct(string $apiKey, Configuration $config = null)
    {
        $this->apiKey = $apiKey;
        $this->config = $config ?? new Configuration();
        $this->httpClient = new HttpClient([
            'base_uri' => 'https://api.assemblyai.com/v2/',
            'headers' => [
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json'
            ]
        ]);
        $this->messageHandler = new MessageHandler();
    }

    public function startRealTimeTranscription(callable $onMessage, array $lemurConfig = []): void
    {
        $this->loop = \React\EventLoop\Factory::create();
        
        $connection = new \Ratchet\Client\Connector($this->loop);
        
        $connection('wss://api.assemblyai.com/v2/realtime/ws?sample_rate=16000', [], [
            'Headers' => [
                'Authorization' => $this->apiKey
            ]
        ])->then(function(WebSocket $conn) use ($onMessage, $lemurConfig) {
            $this->connection = $conn;

            // Send configuration
            $config = [
                'sample_rate' => 16000,
                'word_boost' => [],
                'lemur' => $lemurConfig
            ];
            $conn->send(json_encode($config));

            $conn->on('message', function($msg) use ($onMessage) {
                $data = json_decode($msg, true);
                $this->messageHandler->handle($data, $onMessage);
            });

        }, function ($e) {
            throw new AssemblyAIException("Could not connect: {$e->getMessage()}");
        });

        $this->loop->run();
    }

    public function sendAudioChunk(string $audioData): void
    {
        if (!$this->connection) {
            throw new AssemblyAIException("No active connection");
        }

        $this->connection->send(json_encode([
            'audio_data' => base64_encode($audioData)
        ]));
    }

    public function stop(): void
    {
        if ($this->connection) {
            $this->connection->close();
            $this->connection = null;
        }
        if ($this->loop) {
            $this->loop->stop();
        }
    }
}