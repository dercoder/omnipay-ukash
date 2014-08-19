<?php
namespace Omnipay\Ukash\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Ukash Response
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.0.0
 */
class Response extends AbstractResponse implements RedirectResponseInterface
{
    protected $liveEndpoint = 'https://direct.ukash.com/';
    protected $testEndpoint = 'https://direct.staging.ukash.com/';

    /**
     * Does the response require a redirect?
     * @return boolean
     */
    public function isRedirect()
    {
        return $this->getCode() === 0 and $this->isSuccessful() !== true;
    }

    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->getEndpoint() . 'hosted/entry.aspx?UTID=' . $this->getTransactionReference();
        }
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * Is the response successful?
     *
     * @return boolean is successful
     */
    public function isSuccessful()
    {
        return $this->getCode() === 0 and $this->getTransactionCode() === 0;
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
        $error = $this->getErrDescription();
        $desc = $this->getTransactionDesc();

        if (!empty($desc) and (empty($error) or $error == 'None')) {
            return $desc;
        } else {
            return $error;
        }
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
     * Short text description of the transaction status/return code.
     *
     * @return string transaction desc
     */
    public function getErrDescription()
    {
        return isset($this->data->errDescription) ? (string) $this->data->errDescription : null;
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
     * This will be used to validate the transaction.
     *
     * @return string transaction reference
     */
    protected function getEndpoint()
    {
        return $this->getRequest()->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
