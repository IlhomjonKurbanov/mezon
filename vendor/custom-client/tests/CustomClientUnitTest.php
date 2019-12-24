<?php
require_once (__DIR__ . '/../custom-client.php');

class CustomClientUnitTest extends PHPUnit\Framework\TestCase
{

    /**
     * Testing invalid construction
     */
    public function testConstructorInvalid(): void
    {
        try {
            $Client = new \Mezon\CustomClient(false);
            $this->fail('Exception was not thrown ' . serialize($Client));
        } catch (Exception $e) {
            $this->addToAssertionCount(1);
        }

        try {
            $Client = new \Mezon\CustomClient('');
            $this->fail('Exception was not thrown ' . serialize($Client));
        } catch (Exception $e) {
            $this->addToAssertionCount(1);
        }
    }

    /**
     * Testing valid construction
     */
    public function testConstructorValid(): void
    {
        $Client = new \Mezon\CustomClient('http://yandex.ru/', [
            'header'
        ]);

        $this->assertEquals('http://yandex.ru', $Client->getUrl(), 'Invalid URL');
        $this->assertEquals(1, count($Client->getHeaders()), 'Invalid headers');
    }

    /**
     * Testing getters/setters for the field
     */
    public function testIdempotencyGetSet(): void
    {
        // setup
        $Client = new \Mezon\CustomClient('some url', []);

        // test bodyand assertions
        $Client->setIdempotencyKey('i-key');

        $this->assertEquals('i-key', $Client->getIdempotencyKey(), 'Invalid idempotency key');
    }

    /**
     * Creating mock
     */
    protected function getMock(): object
    {
        $Mock = $this->getMockBuilder('\Mezon\CustomClient')
            ->setMethods([
            'sendRequest'
        ])
            ->disableOriginalConstructor()
            ->getMock();

        return ($Mock);
    }

    /**
     * Testing getRequest method
     */
    public function testGetRequest(): void
    {
        // setup
        $Client = $this->getMock();
        $Client->method('sendRequest')->willReturn([
            'result',
            1
        ]);

        // test body
        $Result = $Client->getRequest('/end-point/');

        // assertions
        $this->assertEquals('result', $Result);
    }

    /**
     * Testing postRequest method
     */
    public function testPostRequest(): void
    {
        // setup
        $Client = $this->getMock();
        $Client->method('sendRequest')->willReturn([
            'result',
            1
        ]);

        // test body
        $Result = $Client->postRequest('/end-point/');

        // assertions
        $this->assertEquals('result', $Result);
    }
}

?>