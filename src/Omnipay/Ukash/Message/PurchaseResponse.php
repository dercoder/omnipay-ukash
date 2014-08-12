<?php
namespace Omnipay\Ukash\Message;

/**
 * Ukash Response for UTID
 *
 * @author Alexander Fedra <contact@dercoder.at>
 * @copyright 2014 DerCoder
 * @license http://opensource.org/licenses/mit-license.php MIT
 * @version 1.3.0 Ukash API Specification
 */
class PurchaseResponse extends Response implements \Omnipay\Common\Message\RedirectResponseInterface
{
    /**
     * Does the response require a redirect?
     * @return boolean
     */
    public function isRedirect()
    {
        return true;
    }

    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        return $this->getEndpoint() . 'hosted/entry.aspx?UTID=' . (string) $this->data->UTID;
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

}
