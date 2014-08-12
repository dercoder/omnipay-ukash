<?php
namespace Omnipay\Ukash\Message;

/**
 * Ukash Complete Purchase Request
 *
 * Once the consumer has completed his transaction, the system will notify the merchant by doing an HTTP POST to the notification URL.
 * The following values will be sent to the merchant, via HTTP POST.
 */
class CompletePurchaseRequest extends FetchTransactionRequest
{

}
