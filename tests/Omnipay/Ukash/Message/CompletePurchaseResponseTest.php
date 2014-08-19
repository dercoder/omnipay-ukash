<?php
namespace Omnipay\Ukash\Message;

use SimpleXMLElement;
use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CompletePurchaseFailure.txt');
        $xmlResponse = new SimpleXMLElement(htmlspecialchars_decode($httpResponse->getBody(true)), LIBXML_NONET);
        $response = new Response($this->getMockRequest(), $xmlResponse->UKashRPP);

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(223, $response->getCode());
        $this->assertNotNull($response->getMessage());

        $this->assertSame(99, $response->getTransactionCode());
        $this->assertSame('Failure', $response->getTransactionDesc());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CompletePurchaseSuccess.txt');
        $xmlResponse = new SimpleXMLElement(htmlspecialchars_decode($httpResponse->getBody(true)), LIBXML_NONET);
        $response = new Response($this->getMockRequest(), $xmlResponse->UKashRPP);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame(0, $response->getCode());
        $this->assertNotNull($response->getMessage());

        $this->assertSame('1LB8a1WWBQQ35Pa8SEST', $response->getTransactionReference());
        $this->assertSame('R2345678901234567890', $response->getSecurityToken());
        $this->assertSame(0, $response->getTransactionCode());
        $this->assertSame('Accepted', $response->getTransactionDesc());
        $this->assertSame('12.34', $response->getAmount());
        $this->assertSame('USD', $response->getCurrency());
    }
}
