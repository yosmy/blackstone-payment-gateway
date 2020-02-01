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
            if ($response['ResponseCode'] == 3) {
                if (isset($response['verbiage'])) {
                    $errors = ['INVALID CARD #', 'CALL 18003372255', 'DECLINED'];

                    foreach ($errors as $error) {
                        if (isset($response['verbiage']) && strpos($response['verbiage'], $error) !== false) {
                            throw new Gateway\IssuerException();
                        }
                    }
                }
            }
        }
    }
}