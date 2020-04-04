<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.gateway.blackstone.add_card.exception_throwed'
 *     ]
 * })
 */
class ProcessFieldApiException implements Gateway\ProcessApiException
{
    /**
     * {@inheritDoc}
     */
    public function process(Gateway\ApiException $e)
    {
        $response = $e->getResponse();

        if (isset($response['ResponseCode'])) {
            if ($response['ResponseCode'] == 25) {
                if (isset($response['verbiage'])) {
                    if (strpos($response['verbiage'], 'LUHN/MOD10 CHECK ON ACCOUNT NUMBER FAILED') !== false) {
                        throw new Gateway\IssuerException('number');
                    } else if (strpos($response['verbiage'], 'INVALID ZIPCODE. NOT VALID FOR US OR CANADA') !== false) {
                        throw new Gateway\IssuerException('zipcode');
                    }
                }
            }
        }
    }
}