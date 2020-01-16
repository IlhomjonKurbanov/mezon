<?php
require_once ('autoload.php');

class ServiceMockSecurityProviderUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Testing session creation.
     */
    public function testCreateSession1()
    {
        $Provider = new \Mezon\Service\ServiceMockSecurityProvider();

        $Token = $Provider->createSession();

        $this->assertEquals(32, strlen($Token), 'Invalid token was returned');
    }
}