<?php

namespace AssemblyAIRealtime\Handlers;

class MessageHandler
{
    public function handle(array $data, callable $callback): void
    {
        if (isset($data['message_type'])) {
            switch ($data['message_type']) {
                case 'FinalTranscript':
                    $this->handleFinalTranscript($data, $callback);
                    break;
                case 'PartialTranscript':
                    $this->handlePartialTranscript($data, $callback);
                    break;
                case 'LemurResult':
                    $this->handleLemurResult($data, $callback);
                    break;
                case 'Error':
                    $this->handleError($data, $callback);
                    break;
            }
        }
    }

    private function handleFinalTranscript(array $data, callable $callback): void
    {
        $callback([
            'type' => 'final',
            'text' => $data['text'] ?? '',
            'words' => $data['words'] ?? [],
            'timestamp' => $data['timestamp'] ?? null
        ]);
    }

    private function handlePartialTranscript(array $data, callable $callback): void
    {
        $callback([
            'type' => 'partial',
            'text' => $data['text'] ?? '',
            'timestamp' => $data['timestamp'] ?? null
        ]);
    }

    private function handleLemurResult(array $data, callable $callback): void
    {
        $callback([
            'type' => 'lemur',
            'response' => $data['response'] ?? '',
            'timestamp' => $data['timestamp'] ?? null
        ]);
    }

    private function handleError(array $data, callable $callback): void
    {
        $callback([
            'type' => 'error',
            'error' => $data['error'] ?? 'Unknown error occurred',
            'timestamp' => $data['timestamp'] ?? null
        ]);
    }
}