<?php

namespace AssemblyAIRealtime\Config;

class Configuration
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'sample_rate' => 16000,
            'word_boost' => [],
            'lemur' => [
                'enabled' => false
            ]
        ], $config);
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function setLemurConfig(array $lemurConfig): void
    {
        $this->config['lemur'] = array_merge($this->config['lemur'], $lemurConfig);
    }
}