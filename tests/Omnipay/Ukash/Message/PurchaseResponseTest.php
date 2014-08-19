<?php
namespace Omnipay\Ukash\Message;

use SimpleXMLElement;
use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $xmlResponse = new SimpleXMLElement(htmlspecialchars_decode($httpResponse->getBody(true)), LIBXML_NONET);
        $response = new Response($this->getMockRequest(), $xmlResponse->UKashRPP);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(203, $response->getCode());
        $this->assertNotNull($response->getMessage());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $xmlResponse = new SimpleXMLElement(htmlspecialchars_decode($httpResponse->getBody(true)), LIBXML_NONET);
        $response = new Response($this->getMockRequest(), $xmlResponse->UKashRPP);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(0, $response->getCode());
        $this->assertNotNull($response->getMessage());

        $this->assertSame('3W7B46B346B4G541D039', $response->getTransactionReference());
        $this->assertSame('R2345678901234567890', $response->getSecurityToken());
    }
}
