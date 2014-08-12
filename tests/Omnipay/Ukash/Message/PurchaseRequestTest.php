<?php
namespace Omnipay\Ukash\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequestTest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $expectedData = array(
            'brandId'               => 'UKASH10082',
            'requestSecurityToken'  => '12345678901234567890',
            'responseSecurityToken' => 'R2345678901234567890',
            'languageCode'          => 'EN',
            'merchantTransactionId' => 'TX4567890',
            'consumerId'            => '123',
            'amount'                => '12.34',
            'currency'              => 'EUR',
        );
        $this->request->initialize($expectedData);

        $data = $this->request->getData();

        $this->assertSame($expectedData['brandId'], $data['BrandID']);
        $this->assertSame($expectedData['requestSecurityToken'], $data['SecurityToken']);
        $this->assertSame($expectedData['languageCode'], $data['LanguageCode']);
        $this->assertSame($expectedData['merchantTransactionId'], $data['MerchantTransactionID']);
        $this->assertSame($expectedData['consumerId'], $data['ConsumerID']);
        $this->assertSame($expectedData['amount'], $data['TransactionValue']);
        $this->assertSame($expectedData['currency'], $data['MerchantCurrency']);
    }
}
