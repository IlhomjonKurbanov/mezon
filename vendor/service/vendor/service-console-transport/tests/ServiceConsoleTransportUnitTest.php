<?php
require_once (__DIR__ . '/../../service-logic/service-logic.php');
require_once (__DIR__ . '/../../service-security-provider/service-security-provider.php');
require_once (__DIR__ . '/../../service-mock-security-provider/service-mock-security-provider.php');
require_once (__DIR__ . '/../../service-console-transport/vendor/console-request-params/console-request-params.php');
require_once (__DIR__ . '/../service-console-transport.php');

class FakeSecurityProvider
{
}

class TestingServiceLogic extends \Mezon\Service\ServiceLogic
{

    public function privateMethod()
    {
        return ('private');
    }

    public function publicMethod()
    {
        return ('public');
    }
}

class ServiceConsoleTransportUnitTest extends PHPUnit\Framework\TestCase
{

    /**
     * Getting mock object.
     *
     * @return object ServiceConsoleTransport mocked object.
     */
    protected function getTransportMock(): object
    {
        $Mock = $this->getMockBuilder(\Mezon\Service\ServiceConsoleTransport::class)
            ->setMethods([
            'createSession'
        ])
            ->getMock();

        return ($Mock);
    }

    /**
     * Getting mock object.
     *
     * @return object ServiceLogic mocked object.
     */
    protected function getServiceLogicMock(): object
    {
        $Mock = $this->getMockBuilder('TestingServiceLogic')
            ->disableOriginalConstructor()
            ->setMethods([
            'connect'
        ])
            ->getMock();

        return ($Mock);
    }

    /**
     * Testing connect method.
     */
    public function testConstructor(): void
    {
        $Transport = new \Mezon\Service\ServiceConsoleTransport();

        $this->assertNotEquals(null, $Transport->SecurityProvider, 'Security provide was not setup');
    }

    /**
     * Testing that security provider was set.
     */
    public function testSecurityProviderInitDefault(): void
    {
        $Transport = new \Mezon\Service\ServiceConsoleTransport();
        $this->assertInstanceOf(\Mezon\Service\ServiceMockSecurityProvider::class, $Transport->SecurityProvider);
    }

    /**
     * Testing that security provider was set.
     */
    public function testSecurityProviderInitString(): void
    {
        $Transport = new \Mezon\Service\ServiceConsoleTransport(FakeSecurityProvider::class);
        $this->assertInstanceOf(FakeSecurityProvider::class, $Transport->SecurityProvider);
    }

    /**
     * Testing that security provider was set.
     */
    public function testSecurityProviderInitObject(): void
    {
        $Transport = new \Mezon\Service\ServiceConsoleTransport(new FakeSecurityProvider());
        $this->assertInstanceOf(FakeSecurityProvider::class, $Transport->SecurityProvider);
    }

    /**
     * Testing that header function is called once for each header.
     */
    public function testSingleHeaderCall(): void
    {
        $Mock = $this->getTransportMock();

        $ServiceLogic = $this->getServiceLogicMock();

        $ServiceLogic->expects($this->once())
            ->method('connect');

        $Mock->callLogic($ServiceLogic, 'connect');
    }

    /**
     * Testing that header function is called once for each header.
     */
    public function testSingleHeaderCallPublic(): void
    {
        $Mock = $this->getTransportMock();

        $ServiceLogic = $this->getServiceLogicMock();

        $ServiceLogic->expects($this->once())
            ->method('connect');

        $Mock->callPublicLogic($ServiceLogic, 'connect');
    }

    /**
     * Testing public call without createSession method.
     */
    public function testPublicCall(): void
    {
        // setup
        $Mock = $this->getTransportMock();

        $Mock->ServiceLogic = $this->getServiceLogicMock();

        $Mock->expects($this->never())
            ->method('createSession');

        $Mock->addRoute('public-method', 'publicMethod', 'GET', 'public_call');

        // test body and assertions
        $Mock->Router->callRoute('/public-method/');
    }

    /**
     * Testing private call with createSession method.
     */
    public function testPrivateCall(): void
    {
        // setup
        $Mock = $this->getTransportMock();

        $Mock->ServiceLogic = $this->getServiceLogicMock();

        $Mock->expects($this->once())
            ->method('createSession');

        $Mock->addRoute('private-method', 'privateMethod', 'GET', 'private_call');

        // test body and assertions
        $Mock->Router->callRoute('/private-method/');
    }

    /**
     * Testing 'run' method
     */
    public function testRun(): void
    {
        // setup
        $_GET['r'] = 'public-method';
        $Mock = $this->getTransportMock();

        $Mock->ServiceLogic = $this->getServiceLogicMock();

        $Mock->expects($this->never())
            ->method('createSession');

        $Mock->addRoute('public-method', 'publicMethod', 'GET', 'public_call');

        // test body
        $Mock->run();

        // assertions
        $this->assertEquals('public', $Mock->Result);
    }
}

?>