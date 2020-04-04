<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.gateway.blackstone.execute_charge.exception_throwed'
 *     ]
 * })
 */
class ProcessIssuerApiException implements Gateway\ProcessApiException
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
                    if (strpos($response['verbiage'], 'UNABLE TO DETERMINE CARDTYPE') !== false) {
                        throw new Gateway\IssuerException();
                    }
                }
            } else if ($response['ResponseCode'] == 3) {
                if (isset($response['verbiage'])) {
                    if (
                        strpos($response['verbiage'], 'INVALID CARD #') !== false
                        || strpos($response['verbiage'], 'CALL 18003372255') !== false
                        || strpos($response['verbiage'], 'DECLINED') !== false
                    ) {
                        throw new Gateway\IssuerException();
                    }
                }
            }
        }
    }
}