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
        //
        // PayPal
        //

        // PayPal payout
        $payouts = $this->paypal->payout(); // returns PayPal\Api\Payout

        // Sender header
        $senderBatchHeader = $this->paypal->payoutSenderBatchHeader(); // returns PayPal\Api\PayoutSenderBatchHeader
        $senderBatchHeader->setSenderBatchId($payout->getBatchId())
            ->setEmailSubject('You have received a payout from ' . env('APP_NAME') . ' !');
        $payouts->setSenderBatchHeader($senderBatchHeader);

        // Payout item
        $senderItem = $this->paypal->payoutItem();  // returns PayPal\Api\PayoutItem
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for your sharing!')
            ->setReceiver($payout->email)
            ->setSenderItemId(env('APP_NAME') . ' shares')
            ->setAmount(new \PayPal\Api\Currency('{
                        "value":"'.$payout->amount.'",
                        "currency":"AUD"
                    }'));
        $payouts->addItem($senderItem);

        // Payout create
        try {
            $payouts->create([], $this->paypal->getApiContext());
        } catch (\Exception $e) {

        }
    }

}