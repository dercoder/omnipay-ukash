<?php
namespace Omnipay\Ukash\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse->getBody(true));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(203, $response->getCode());
        $this->assertNotNull($response->getMessage());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new PurchaseResponse($this->getMockRequest(), $httpResponse->getBody(true));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(0, $response->getCode());
        $this->assertNotNull($response->getMessage());

        $this->assertSame('3W7B46B346B4G541D039', $response->getTransactionReference());
        $this->assertSame('R2345678901234567890', $response->getSecurityToken());
    }
}
