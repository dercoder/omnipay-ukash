<?php
namespace Omnipay\Ukash;

use Omnipay\Common\AbstractGateway;

/**
 * Ukash Gateway
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.0.0
 */
class Gateway extends AbstractGateway
{

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Ukash';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'brandId' => 0,
            'languageCode' => 'EN',
            'requestSecurityToken' => '',
            'responseSecurityToken' => '',
            'testMode'  => false,
        );
    }

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
     * @return string merchant key
     */
    public function getRequestSecurityToken()
    {
        return $this->getParameter('requestSecurityToken');
    }

    /**
     * Set the Request Security Token were assigned when your Ukash merchant account was
     * created.
     *
     * 20-Character alphanumeric unique Request-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @param  string $value merchant key
     * @return self
     */
    public function setRequestSecurityToken($value)
    {
        return $this->setParameter('requestSecurityToken', $value);
    }

    /**
     * Get the Request Security Token
     *
     * 20-Character alphanumeric unique Response-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @return string merchant key
     */
    public function getResponseSecurityToken()
    {
        return $this->getParameter('responseSecurityToken');
    }

    /**
     * Set the Request Security Token were assigned when your Ukash merchant account was
     * created.
     *
     * 20-Character alphanumeric unique Response-Token provided by Ukash.
     * The token is used to validate the merchant account.
     *
     * @param  string $value merchant key
     * @return self
     */
    public function setResponseSecurityToken($value)
    {
        return $this->setParameter('responseSecurityToken', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ukash\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ukash\Message\CompletePurchaseRequest', $parameters);
    }

    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Ukash\Message\FetchTransactionRequest', $parameters);
    }

}
