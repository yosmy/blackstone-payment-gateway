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
                throw new Gateway\FieldException(null);
            }
        }
    }
}