<?php

namespace AssemblyAIRealtime\Utils;

class LemurUtils
{
    /**
     * Create Lemur configuration
     */
    public static function createLemurConfig(string $task, string $prompt): array
    {
        return [
            'enabled' => true,
            'task' => $task,
            'prompt' => $prompt
        ];
    }

    /**
     * Validate Lemur task type
     */
    public static function validateTask(string $task): bool
    {
        $validTasks = [
            'summarize',
            'question_answer',
            'action_items',
            'key_points'
        ];

        return in_array($task, $validTasks);
    }

    /**
     * Format Lemur response
     */
    public static function formatResponse(array $response): array
    {
        return [
            'type' => 'lemur',
            'task' => $response['task'] ?? '',
            'response' => $response['response'] ?? '',
            'confidence' => $response['confidence'] ?? 0.0,
            'timestamp' => $response['timestamp'] ?? null
        ];
    }
}