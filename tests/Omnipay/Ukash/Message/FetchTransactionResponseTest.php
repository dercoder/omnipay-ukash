<?php
namespace Omnipay\Ukash\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionResponseTest extends TestCase
{
    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionFailure.txt');
        $response = new FetchTransactionResponse($this->getMockRequest(), $httpResponse->getBody(true));

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(223, $response->getCode());
        $this->assertNotNull($response->getMessage());

        $this->assertSame(99, $response->getTransactionCode());
        $this->assertSame('Failure', $response->getTransactionDesc());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionSuccess.txt');
        $response = new FetchTransactionResponse($this->getMockRequest(), $httpResponse->getBody(true));

        $this->assertFalse($response->isSuccessful());
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
