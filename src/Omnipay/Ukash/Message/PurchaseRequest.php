<?php
namespace Omnipay\Ukash\Message;

use SimpleXMLElement;

/**
 * Ukash Request for UTID
 *
 * The second step in the RPP process is to acquire a Unique Transaction ID.
 * The method will return a string value containing a URL encoded XML string.
 * The XML tags returned are listed below.
 *
 * Two API methods exists to obtain an UTID. Default and ExtraLogging.
 *
 * Ukash has the ability to make certain additional fields mandatory when requesting a new UTID.
 * In such a case please use the ExtraLogging url specified below and provide the mandatory fields required by Ukash.
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.3.0 Ukash API Specification
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * Get the 2-letter language code that indicates the member's language preference.
     *
     * @return string language code
     */
    public function getLanguageCode()
    {
        return $this->getParameter('languageCode');
    }

    /**
     * Set the 2-letter language code that indicates the member's language preference.
     *
     * Please note that Ukasj supports only English (EN).
     *
     * @param  string $value language code
     * @return self
     */
    public function setLanguageCode($value)
    {
        return $this->setParameter('languageCode', $value);
    }

    /**
     * A unique reference to the transaction must be supplied by the merchant.
     *
     * @return string merchant transaction id
     */
    public function getMerchantTransactionId()
    {
        return $this->getParameter('merchantTransactionId');
    }

    /**
     * A unique reference to the transaction must be supplied by the merchant.
     *
     * @param  string $value merchant transaction id
     * @return self
     */
    public function setMerchantTransactionId($value)
    {
        return $this->setParameter('merchantTransactionId', $value);
    }

    /**
     * Unique reference per consumer.
     * Info is required for fraud analysis.
     *
     * @return string consumer id
     */
    public function getConsumerId()
    {
        return $this->getParameter('consumerId');
    }

    /**
     * Unique reference per consumer.
     * Info is required for fraud analysis.
     *
     * @param  string $value consumer id
     * @return self
     */
    public function setConsumerId($value)
    {
        return $this->setParameter('consumerId', $value);
    }

    /**
     * Saves a user email address
     * Optional field
     *
     * @return string user email address
     */
    public function getUserEmailAddress()
    {
        return $this->getParameter('userEmailAddress');
    }

    /**
     * Saves a user email address
     * Optional field
     *
     * @param  string $value user email address
     * @return self
     */
    public function setUserEmailAddress($value)
    {
        return $this->setParameter('userEmailAddress', $value);
    }

    /**
     * Saves a unique user id
     * Eg. johnd123
     * Optional field
     *
     * @return string user unique id
     */
    public function getUserUniqueId()
    {
        return $this->getParameter('userUniqueId');
    }

    /**
     * Saves a unique user id
     * Eg. johnd123
     * Optional field
     *
     * @param  string $value user unique id
     * @return self
     */
    public function setUserUniqueId($value)
    {
        return $this->setParameter('userUniqueId', $value);
    }

    /**
     * Saves a unique user id
     * Eg. UK
     * Optional field
     *
     * Please ensure that it corresponds to the Ukash list of countries ISO values
     *
     * @return string user country
     */
    public function getUserCountry()
    {
        return $this->getParameter('userUniqueId');
    }

    /**
     * Saves user country
     * Eg. UK
     * Optional field
     *
     * Please ensure that it corresponds to the Ukash list of countries ISO values
     *
     * @param  string $value user country
     * @return self
     */
    public function setUserCountry($value)
    {
        return $this->setParameter('userUniqueId', $value);
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
            'languageCode',
            'merchantTransactionId',
            'consumerId',
            'amount',
            'currency'
        );

        $data['LanguageCode'] = $this->getLanguageCode();
        $data['MerchantTransactionID'] = $this->getMerchantTransactionId();
        $data['MerchantCurrency'] = $this->getCurrency();
        $data['TransactionValue'] = $this->getAmount();
        $data['ConsumerID'] = $this->getConsumerId();
        $data['URL_Success'] = $this->getReturnUrl();
        $data['URL_Fail'] = $this->getCancelUrl();
        $data['URL_Notification'] = $this->getNotifyUrl();
        $data['UserEmailAddress'] = $this->getUserEmailAddress();
        $data['UserUniqueID'] = $this->getUserUniqueId();
        $data['UserCountry'] = $this->getUserCountry();
        $data['UserIP'] = $this->getClientIp();

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed             $data The data to send
     * @return PurchaseResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest('POST', 'RPPGateway/process.asmx/GetUniqueTransactionID', $data);
        return new PurchaseResponse($this, $response);
    }

}
