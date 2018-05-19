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
        $output = $payouts->create([], $this->paypal->getApiContext());

/*
        PayoutBatch {#890
        -_propMap: array:2 [
            "batch_header" => PayoutBatchHeader {#893
            -_propMap: array:3 [
                "payout_batch_id" => "3DJFX4BAYR3G6"
        "batch_status" => "PENDING"
        "sender_batch_header" => PayoutSenderBatchHeader {#896
                -_propMap: array:2 [
                    "sender_batch_id" => "55165ec088524bf5addbc0dfe53c50"
            "email_subject" => "You have received a payout from Socialise !"
          ]
        }
      ]
    }
    "links" => array:1 [
            0 => Links {#899
            -_propMap: array:4 [
                "href" => "https://api.sandbox.paypal.com/v1/payments/payouts/3DJFX4BAYR3G6"
          "rel" => "self"
          "method" => "GET"
          "enctype" => "application/json"
        ]
      }
    ]
  ]
}
        */
    }

}