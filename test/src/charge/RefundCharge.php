<?php

namespace Yosmy\Payment\Gateway\Blackstone\Test;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Blackstone;
use LogicException;

/**
 * @di\service()
 */
class RefundCharge
{
    /**
     * @var Blackstone\RefundCharge
     */
    private $refundCharge;

    /**
     * @param Blackstone\RefundCharge $refundCharge
     */
    public function __construct(Blackstone\RefundCharge $refundCharge)
    {
        $this->refundCharge = $refundCharge;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/blackstone/refund-charge"})
     *
     * @param string $id
     */
    public function delete(
        string $id
    ) {
        try {
            $this->refundCharge->refund(
                $id
            );
        } catch (Gateway\ApiException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}