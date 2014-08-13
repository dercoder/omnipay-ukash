<?php
namespace Omnipay\Ukash\Message;

/**
 * Ukash Response for Transaction Status
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.3.0 Ukash API Specification
 */
class FetchTransactionResponse extends Response
{
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
        return (int) $this->data->TransactionCode;
    }

    /**
     * Short text description of the transaction status/return code.
     *
     * @return string transaction desc
     */
    public function getTransactionDesc()
    {
        return (string) $this->data->TransactionDesc;
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
}
