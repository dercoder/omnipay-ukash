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
     * Get the total amount of the transfer as it was requested.
     *
     * The amount will be greater than 0.01 units of currency in the supported currency
     * and may have decimal places, but no currency symbols.
     *
     * @return string amount
     */
    public function getAmount()
    {
        return (string) $this->data->SettleAmount;
    }

    /**
     * Get the currency of the transfer as it was requested.
     *
     * @return string amount
     */
    public function getCurrency()
    {
        return (string) $this->data->MerchantCurrency;
    }

    /**
     * Transaction status/return code.
     * It determines whether the voucher was successfully redeemed or not.
     * A “0” means that the voucher was successfully redeemed.
     * Any other code will reflect an unsuccessful redemption due to an
     * invalid voucher or an error.
     *
     * 0 => Accepted
     * Redemption successful
     *
     * 1 => Declined
     * Redemption unsuccessful
     *
     * 99 => Failed
     * An error occurred during the processing of the transaction hence the system
     * could not successfully complete the redemption of the voucher.
     * Will also be returned if an invalid voucher number was supplied.
     *
     * @return string transaction code
     */
    public function getTransactionCode()
    {
        return isset($this->data->TransactionCode) ? (int) $this->data->TransactionCode : null;
    }

    /**
     * Short text description of the transaction status/return code.
     *
     * @return string transaction desc
     */
    public function getTransactionDesc()
    {
        return isset($this->data->TransactionDesc) ? (string) $this->data->TransactionDesc : null;
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
