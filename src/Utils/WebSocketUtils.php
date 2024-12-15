<?php

namespace AssemblyAIRealtime\Utils;

class WebSocketUtils
{
    /**
     * Create WebSocket connection parameters
     */
    public static function createConnectionParams(string $apiKey, array $config): array
    {
        return [
            'headers' => [
                'Authorization' => $apiKey
            ],
            'timeout' => $config['timeout'] ?? 30,
            'fragment_size' => $config['fragment_size'] ?? 4096
        ];
    }

    /**
     * Format message for WebSocket transmission
     */
    public static function formatMessage(array $data): string
    {
        return json_encode($data);
    }

    /**
     * Handle WebSocket reconnection
     */
    public static function handleReconnection(int $attempts = 0, int $maxAttempts = 3): bool
    {
        if ($attempts >= $maxAttempts) {
            return false;
        }

        $backoffTime = pow(2, $attempts) * 1000; // Exponential backoff
        usleep($backoffTime * 1000); // Convert to microseconds
        return true;
    }
}