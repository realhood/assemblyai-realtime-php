<?php

namespace Tests\Utils;

use PHPUnit\Framework\TestCase;
use AssemblyAIRealtime\Utils\LemurUtils;

class LemurUtilsTest extends TestCase
{
    public function testCreateLemurConfig()
    {
        $task = 'summarize';
        $prompt = 'Test prompt';

        $config = LemurUtils::createLemurConfig($task, $prompt);

        $this->assertArrayHasKey('enabled', $config);
        $this->assertArrayHasKey('task', $config);
        $this->assertArrayHasKey('prompt', $config);
        $this->assertTrue($config['enabled']);
        $this->assertEquals($task, $config['task']);
        $this->assertEquals($prompt, $config['prompt']);
    }

    public function testValidateTask()
    {
        $this->assertTrue(LemurUtils::validateTask('summarize'));
        $this->assertTrue(LemurUtils::validateTask('question_answer'));
        $this->assertTrue(LemurUtils::validateTask('action_items'));
        $this->assertTrue(LemurUtils::validateTask('key_points'));
        $this->assertFalse(LemurUtils::validateTask('invalid_task'));
    }

    public function testFormatResponse()
    {
        $response = [
            'task' => 'summarize',
            'response' => 'Test response',
            'confidence' => 0.95
        ];

        $formatted = LemurUtils::formatResponse($response);

        $this->assertArrayHasKey('type', $formatted);
        $this->assertArrayHasKey('task', $formatted);
        $this->assertArrayHasKey('response', $formatted);
        $this->assertArrayHasKey('confidence', $formatted);
        $this->assertArrayHasKey('timestamp', $formatted);
        $this->assertEquals('lemur', $formatted['type']);
    }
}