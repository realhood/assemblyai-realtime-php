<?php

namespace Tests\Utils;

use PHPUnit\Framework\TestCase;
use AssemblyAIRealtime\Utils\WebSocketUtils;

class WebSocketUtilsTest extends TestCase
{
    public function testCreateConnectionParams()
    {
        $apiKey = 'test-api-key';
        $config = ['timeout' => 60];

        $params = WebSocketUtils::createConnectionParams($apiKey, $config);

        $this->assertArrayHasKey('headers', $params);
        $this->assertArrayHasKey('Authorization', $params['headers']);
        $this->assertEquals($apiKey, $params['headers']['Authorization']);
        $this->assertEquals(60, $params['timeout']);
    }

    public function testFormatMessage()
    {
        $data = ['test' => 'value'];
        $message = WebSocketUtils::formatMessage($data);

        $this->assertJson($message);
        $this->assertEquals(json_encode($data), $message);
    }

    public function testHandleReconnection()
    {
        // Test successful reconnection
        $this->assertTrue(WebSocketUtils::handleReconnection(0, 3));
        $this->assertTrue(WebSocketUtils::handleReconnection(1, 3));
        $this->assertTrue(WebSocketUtils::handleReconnection(2, 3));

        // Test failed reconnection
        $this->assertFalse(WebSocketUtils::handleReconnection(3, 3));
    }
}