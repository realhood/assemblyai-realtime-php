<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AssemblyAIRealtime\Client\AssemblyAIClient;
use AssemblyAIRealtime\Config\Configuration;

$apiKey = 'your-api-key';

// Create configuration with Lemur enabled
$config = new Configuration([
    'lemur' => [
        'enabled' => true,
        'task' => 'summarize',
        'prompt' => 'Provide a brief summary of the conversation'
    ]
]);

// Initialize client
$client = new AssemblyAIClient($apiKey, $config);

// Callback to handle messages
$messageHandler = function($result) {
    switch ($result['type']) {
        case 'partial':
            echo "Partial transcript: " . $result['text'] . "\n";
            break;
        case 'final':
            echo "Final transcript: " . $result['text'] . "\n";
            break;
        case 'lemur':
            echo "Lemur response: " . $result['response'] . "\n";
            break;
        case 'error':
            echo "Error: " . $result['error'] . "\n";
            break;
    }
};

try {
    // Start real-time transcription
    $client->startRealTimeTranscription($messageHandler);

    // Example: Send audio chunks (you would replace this with your audio input)
    while (true) {
        $audioChunk = // Get your audio chunk here
        $client->sendAudioChunk($audioChunk);
        usleep(100000); // Sleep for 100ms
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    $client->stop();
}