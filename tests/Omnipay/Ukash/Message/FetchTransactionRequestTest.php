<?php
namespace Omnipay\Ukash\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    /**
     * @var FetchTransactionRequestTest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();
        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetData()
    {
        $expectedData = array(
            'brandId'               => 'UKASH10082',
            'requestSecurityToken'  => '12345678901234567890',
            'responseSecurityToken' => 'R2345678901234567890',
            'utId'                  => 'M6LV13CFGVG0F31PSEST',
        );
        $this->request->initialize($expectedData);

        $data = $this->request->getData();

        $this->assertSame($expectedData['brandId'], $data['BrandID']);
        $this->assertSame($expectedData['requestSecurityToken'], $data['SecurityToken']);
        $this->assertSame($expectedData['utId'], $data['UTID']);
    }
}
