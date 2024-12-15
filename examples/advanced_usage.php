<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AssemblyAIRealtime\Client\AssemblyAIClient;
use AssemblyAIRealtime\Config\Configuration;
use AssemblyAIRealtime\Utils\AudioUtils;
use AssemblyAIRealtime\Utils\LemurUtils;

// Initialize with advanced configuration
$config = new Configuration([
    'sample_rate' => 16000,
    'word_boost' => ['important', 'keywords'],
    'lemur' => LemurUtils::createLemurConfig(
        'summarize',
        'Provide a detailed summary with key points'
    )
]);

$client = new AssemblyAIClient('your-api-key', $config);

// Advanced message handler with all possible events
$messageHandler = function($result) {
    switch ($result['type']) {
        case 'partial':
            echo "Partial transcript: " . $result['text'] . "\n";
            echo "Confidence: " . ($result['confidence'] ?? 'N/A') . "\n";
            break;

        case 'final':
            echo "Final transcript: " . $result['text'] . "\n";
            echo "Confidence: " . ($result['confidence'] ?? 'N/A') . "\n";
            if (!empty($result['words'])) {
                echo "Words: " . json_encode($result['words']) . "\n";
            }
            break;

        case 'lemur':
            echo "Lemur Analysis:\n";
            echo "Task: " . ($result['task'] ?? 'N/A') . "\n";
            echo "Response: " . ($result['response'] ?? 'N/A') . "\n";
            echo "Confidence: " . ($result['confidence'] ?? 'N/A') . "\n";
            break;

        case 'error':
            echo "Error occurred: " . $result['error'] . "\n";
            break;
    }
};

try {
    // Start transcription
    $client->startRealTimeTranscription($messageHandler);

    // Process audio file
    $audioPath = 'path/to/your/audio.wav';
    if (AudioUtils::validateAudioFormat($audioPath)) {
        $duration = AudioUtils::getAudioDuration($audioPath);
        echo "Processing audio file (duration: {$duration}s)\n";

        $audioChunks = AudioUtils::convertAudioToFormat($audioPath);
        foreach ($audioChunks as $chunk) {
            $client->sendAudioChunk($chunk);
            usleep(20000); // 20ms delay between chunks
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    $client->stop();
}