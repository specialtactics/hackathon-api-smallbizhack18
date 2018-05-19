<?php

namespace App\Services;

use PulkitJalan\PayPal\PayPal;
use App\Models\Payout;


/**
 * Class PaymentService
 *
 * @package App\Services
 */
class PaymentService
{
    protected $paypal;

    public function __construct(PayPal $paypal)
    {
        $this->paypal = $paypal;
    }

    public function payout(Payout $payout)
    {
        //dd($payout);
        $payouts = $this->paypal->payout(); // returns PayPal\Api\Payout
        $senderBatchHeader = $this->paypal->payoutSenderBatchHeader(); // returns PayPal\Api\PayoutSenderBatchHeader

        $senderItem = $this->paypal->payoutItem();  // returns PayPal\Api\PayoutItem

        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);

        $payout->create([], $this->paypal->getApiContext());
    }

}