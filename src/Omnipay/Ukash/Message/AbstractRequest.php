<?php
namespace Omnipay\Ukash\Message;

use SimpleXMLElement;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Ukash Abstract Request
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.0.0
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://processing.ukash.com/';
    protected $testEndpoint = 'https://processing.staging.ukash.com/';

    /**
     * Get the Brand ID
     *
     * Ukash will supply a brand id to the merchant for each of the brands they wish to differentiate between.
     * The appropriate brand id must then be sent through on each transaction request.
     *
     * @return string brand id
     */
    public function getBrandId()
    {
        return $this->getParameter('brandId');
    }

    /**
     * Set the Brand ID
     *
     * Ukash will supply a brand id to the merchant for each of the brands they wish to differentiate between.
     * The appropriate brand id must then be sent through on each transaction request.
     *
     * @param  string $value brand id
     * @return self
     */
    public function setBrandId($value)
    {
        return $this->setParameter('brandId', $value);
    }

    /**
     * Get the Request Security Token
     *
     * 20-Character alphanumeric unique Request-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @return string request security token
     */
    public function getRequestSecurityToken()
    {
        return $this->getParameter('requestSecurityToken');
    }

    /**
     * Set the Request Security Token
     *
     * 20-Character alphanumeric unique Request-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @param  string $value request security token
     * @return self
     */
    public function setRequestSecurityToken($value)
    {
        return $this->setParameter('requestSecurityToken', $value);
    }

    /**
     * Get the Response Security Token
     *
     * 20-Character alphanumeric unique Request-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @return string response security token
     */
    public function getResponseSecurityToken()
    {
        return $this->getParameter('responseSecurityToken');
    }

    /**
     * Set the Response Security Token
     *
     * 20-Character alphanumeric unique Response-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @param  string $value response security token
     * @return self
     */
    public function setResponseSecurityToken($value)
    {
        return $this->setParameter('responseSecurityToken', $value);
    }

    /**
     * Get the data for this request.
     *
     * @return array request data
     */
    public function getData()
    {
        $this->validate(
            'brandId',
            'requestSecurityToken',
            'responseSecurityToken'
        );

        $data['BrandID'] = $this->getBrandId();
        $data['SecurityToken'] = $this->getRequestSecurityToken();

        return $data;
    }

    /**
     * Get the endpoint for this request.
     *
     * @return string endpoint
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Send request
     *
     * @param  string $method http method
     * @param  string $action http action
     * @param  mixed  $data   http data
     * @return string response
     */
    public function sendRequest($method, $action, $data = null)
    {
        $httpRequest = $this->httpClient->createRequest($method, $this->getEndpoint().$action, null, $data);
        $httpResponse = $httpRequest->send();

        $xml = new SimpleXMLElement(htmlspecialchars_decode($httpResponse->getBody(true)), LIBXML_NONET);

        if (isset($xml->UKashRPP)) {
            throw new InvalidResponseException('Missing "UKashRPP" element in XML response');
        }

        if (!isset($xml->UKashRPP->SecurityToken)) {
            throw new InvalidResponseException('Missing "UKashRPP/SecurityToken" element in XML response');
        }

        if ($xml->UKashRPP->SecurityToken != $this->getResponseSecurityToken()) {
            throw new InvalidResponseException('Invalid SecurityToken in XML response');
        }

        return $xml->UKashRPP;
    }
}
