# AssemblyAI Real-time PHP Package

A comprehensive PHP package for real-time transcription and Lemur integration with AssemblyAI. This package provides a robust implementation for real-time audio transcription and AI-powered analysis using AssemblyAI's WebSocket API and Lemur capabilities.

## Features

- 🎙️ Real-time audio transcription
- 🤖 Lemur AI integration for advanced analysis
- 📝 Support for multiple Lemur tasks (summarization, Q&A, action items)
- 🔄 WebSocket-based streaming with automatic reconnection
- ⚡ Efficient audio processing and chunking
- 🛠️ Comprehensive error handling
- 🧪 Full test coverage

## Requirements

- PHP 7.4 or higher
- Composer
- AssemblyAI API key

## Installation

```bash
composer require realhood/assemblyai-realtime
```

## Quick Start

```php
use AssemblyAIRealtime\Client\AssemblyAIClient;
use AssemblyAIRealtime\Config\Configuration;

// Initialize client with Lemur
$config = new Configuration([
    'lemur' => [
        'enabled' => true,
        'task' => 'summarize',
        'prompt' => 'Provide a brief summary'
    ]
]);

$client = new AssemblyAIClient('your-api-key', $config);

// Handle transcription results
$messageHandler = function($result) {
    switch ($result['type']) {
        case 'partial':
            echo "Partial: {$result['text']}\n";
            break;
        case 'final':
            echo "Final: {$result['text']}\n";
            break;
        case 'lemur':
            echo "Lemur: {$result['response']}\n";
            break;
    }
};

// Start transcription
$client->startRealTimeTranscription($messageHandler);

// Send audio data
$client->sendAudioChunk($audioData);

// Stop when done
$client->stop();
```

## Advanced Usage

### Audio Processing

```php
use AssemblyAIRealtime\Utils\AudioUtils;

// Validate audio format
if (AudioUtils::validateAudioFormat($audioPath)) {
    // Get audio duration
    $duration = AudioUtils::getAudioDuration($audioPath);
    
    // Convert and process audio
    $chunks = AudioUtils::convertAudioToFormat($audioPath);
    foreach ($chunks as $chunk) {
        $client->sendAudioChunk($chunk);
        usleep(20000); // 20ms delay
    }
}
```

### Lemur Integration

```php
use AssemblyAIRealtime\Utils\LemurUtils;

// Create Lemur configuration
$lemurConfig = LemurUtils::createLemurConfig(
    'summarize',
    'Provide a detailed summary'
);

// Available Lemur tasks
$tasks = [
    'summarize',
    'question_answer',
    'action_items',
    'key_points'
];
```

### Error Handling

```php
try {
    $client->startRealTimeTranscription($handler);
} catch (AssemblyAIException $e) {
    echo "AssemblyAI Error: " . $e->getMessage();
} catch (TranscriptionException $e) {
    echo "Transcription Error: " . $e->getMessage();
} catch (LemurException $e) {
    echo "Lemur Error: " . $e->getMessage();
}
```

## Testing

Run the test suite:

```bash
./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Credits

Created and maintained by [Reza Aleyasin](https://github.com/realhood).