<?php
namespace Omnipay\Ukash\Message;

/**
 * Ukash TransactionStatus Request
 *
 * The merchant can verify the transaction status by making a HTTP POST Request to the Transaction status web method.
 * The method will return a string value containing a URL encoded XML string. The XML tags returned are listed below.
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.0.0
 */
class FetchTransactionRequest extends AbstractRequest
{
    /**
     * Get the Response Security Token
     *
     * 20-Character alphanumeric unique Request-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @return string response security token
     */
    public function getUtid()
    {
        return $this->getParameter('utId');
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
    public function setUtid($value)
    {
        return $this->setParameter('utId', $value);
    }

    /**
     * Get the data for this request.
     *
     * @return array request data
     */
    public function getData()
    {
        $data = parent::getData();

        $this->validate(
            'utId'
        );

        $data['UTID'] = $this->getUtid();

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed                    $data The data to send
     * @return FetchTransactionResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest('POST', 'RPPGateway/process.asmx/GetTransactionStatus', $data);

        return new FetchTransactionResponse($this, $response);
    }
}
