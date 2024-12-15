<?php

namespace AssemblyAIRealtime\Utils;

class AudioUtils
{
    /**
     * Convert audio file to correct format for streaming
     */
    public static function convertAudioToFormat(string $audioPath): array
    {
        if (!file_exists($audioPath)) {
            throw new \InvalidArgumentException("Audio file not found: {$audioPath}");
        }

        // Read audio file in chunks
        $chunkSize = 4096; // 4KB chunks
        $audioChunks = [];
        $handle = fopen($audioPath, 'rb');

        while (!feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            if ($chunk !== false) {
                $audioChunks[] = $chunk;
            }
        }

        fclose($handle);
        return $audioChunks;
    }

    /**
     * Calculate audio duration in seconds
     */
    public static function getAudioDuration(string $audioPath): float
    {
        // This is a simplified version. In production, you'd want to use something like FFmpeg
        $fileSize = filesize($audioPath);
        $sampleRate = 16000; // Default sample rate
        $bitsPerSample = 16;
        $channels = 1; // Mono

        return ($fileSize * 8) / ($sampleRate * $bitsPerSample * $channels);
    }

    /**
     * Validate audio format
     */
    public static function validateAudioFormat(string $audioPath): bool
    {
        $allowedExtensions = ['wav', 'mp3', 'm4a'];
        $extension = strtolower(pathinfo($audioPath, PATHINFO_EXTENSION));
        return in_array($extension, $allowedExtensions);
    }
}