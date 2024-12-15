<?php

namespace AssemblyAIRealtime\Services;

use AssemblyAIRealtime\Utils\AudioUtils;
use AssemblyAIRealtime\Utils\WebSocketUtils;
use AssemblyAIRealtime\Exceptions\TranscriptionException;

class TranscriptionService
{
    private $websocket;
    private $config;

    public function __construct($websocket, array $config)
    {
        $this->websocket = $websocket;
        $this->config = $config;
    }

    /**
     * Process audio stream
     */
    public function processAudioStream(string $audioPath): void
    {
        if (!AudioUtils::validateAudioFormat($audioPath)) {
            throw new TranscriptionException("Invalid audio format");
        }

        $audioChunks = AudioUtils::convertAudioToFormat($audioPath);
        
        foreach ($audioChunks as $chunk) {
            $this->sendAudioChunk($chunk);
            usleep(20000); // 20ms delay between chunks
        }
    }

    /**
     * Send audio chunk
     */
    private function sendAudioChunk(string $chunk): void
    {
        $message = WebSocketUtils::formatMessage([
            'audio_data' => base64_encode($chunk)
        ]);

        $this->websocket->send($message);
    }

    /**
     * Handle transcription result
     */
    public function handleTranscriptionResult(array $result): array
    {
        return [
            'type' => $result['message_type'] === 'FinalTranscript' ? 'final' : 'partial',
            'text' => $result['text'] ?? '',
            'confidence' => $result['confidence'] ?? 0.0,
            'words' => $result['words'] ?? [],
            'timestamp' => $result['timestamp'] ?? null
        ];
    }
}