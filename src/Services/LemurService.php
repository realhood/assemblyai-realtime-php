<?php

namespace AssemblyAIRealtime\Services;

use AssemblyAIRealtime\Utils\LemurUtils;
use AssemblyAIRealtime\Exceptions\LemurException;

class LemurService
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Process Lemur request
     */
    public function processLemurRequest(string $text, string $task, string $prompt): array
    {
        if (!LemurUtils::validateTask($task)) {
            throw new LemurException("Invalid Lemur task type");
        }

        $lemurConfig = LemurUtils::createLemurConfig($task, $prompt);
        
        return $this->handleLemurResponse([
            'task' => $task,
            'text' => $text,
            'config' => $lemurConfig
        ]);
    }

    /**
     * Handle Lemur response
     */
    private function handleLemurResponse(array $data): array
    {
        return LemurUtils::formatResponse([
            'task' => $data['task'],
            'response' => $data['response'] ?? '',
            'confidence' => $data['confidence'] ?? 0.0,
            'timestamp' => time()
        ]);
    }
}