<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AssemblyAIRealtime\Client\AssemblyAIClient;
use AssemblyAIRealtime\Config\Configuration;
use AssemblyAIRealtime\Utils\LemurUtils;

// Example 1: Summarization
function runSummarization($apiKey, $audioPath) {
    $config = new Configuration([
        'lemur' => LemurUtils::createLemurConfig(
            'summarize',
            'Provide a concise summary of the main points discussed'
        )
    ]);

    $client = new AssemblyAIClient($apiKey, $config);
    // ... implementation
}

// Example 2: Action Items
function extractActionItems($apiKey, $audioPath) {
    $config = new Configuration([
        'lemur' => LemurUtils::createLemurConfig(
            'action_items',
            'Extract all action items and tasks mentioned'
        )
    ]);

    $client = new AssemblyAIClient($apiKey, $config);
    // ... implementation
}

// Example 3: Question Answering
function answerQuestions($apiKey, $audioPath, array $questions) {
    $config = new Configuration([
        'lemur' => LemurUtils::createLemurConfig(
            'question_answer',
            implode("\n", $questions)
        )
    ]);

    $client = new AssemblyAIClient($apiKey, $config);
    // ... implementation
}

// Example usage
$apiKey = 'your-api-key';
$audioPath = 'path/to/audio.wav';

// Run examples
runSummarization($apiKey, $audioPath);

extractActionItems($apiKey, $audioPath);

$questions = [
    "What were the main topics discussed?",
    "What decisions were made?",
    "What are the next steps?"
];
answerQuestions($apiKey, $audioPath, $questions);