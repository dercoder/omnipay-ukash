<?php
namespace Omnipay\Ukash\Message;

use SimpleXMLElement;

/**
 * Ukash Response
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.0.0
 */
class Response extends \Omnipay\Common\Message\AbstractResponse
{
    protected $liveEndpoint = 'https://direct.ukash.com/';
    protected $testEndpoint = 'https://direct.staging.ukash.com/';

    /**
     * Constructor
     *
     * @param object $request request
     * @param string $data    data
     */
    public function __construct($request, $data)
    {
        $this->request = $request;
        $xml = new SimpleXMLElement(htmlspecialchars_decode($data), LIBXML_NONET);
        $this->data = $xml->UKashRPP;
    }

    /**
     * Is the response successful?
     *
     * @return boolean is successful
     */
    public function isSuccessful()
    {
        if ($this->data->errCode !== '0') {
            return false;
        } elseif (!isset($this->data->TransactionCode)) {
            return false;
        } elseif ($this->data->TransactionCode !== '0') {
            return false;
        } elseif ($this->data->SecurityToken != $this->getRequest()->getResponseSecurityToken()) {
            return false;
        }

        return true;
    }

    /**
     * Get the response code.
     *
     * @return int code
     */
    public function getCode()
    {
        return (int) $this->data->errCode;
    }

    /**
     * Get the response message.
     *
     * @return string message
     */
    public function getMessage()
    {
        if (!empty($this->data->errDescription)) {
            return (string) $this->data->errDescription;
        }

        if ($this->data->SecurityToken != $this->getSecurityToken()) {
            return 'SecurityToken does not match. Please verify your settings';
        }

        if (isset($this->data->TransactionDesc)) {
            return (string) $this->data->TransactionDesc;
        }

        return '';
    }

    /**
     * Get the unique ID that identifies the transaction in the Ukash system.
     *
     * @return string transaction reference
     */
    public function getTransactionReference()
    {
        return (string) $this->data->UTID;
    }

    /**
     * Get the Response Security Token
     *
     * 20-Character alphanumeric unique Request-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @return string response security token
     */
    public function getSecurityToken()
    {
        return (string) $this->data->SecurityToken;
    }

    /**
     * Get the unique ID that identifies the transaction in the Ukash system.
     *
     * This will be used to validate the transaction.
     *
     * @return string transaction reference
     */
    protected function getEndpoint()
    {
        return $this->getRequest()->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
