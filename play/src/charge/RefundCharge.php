<?php

namespace Yosmy\Payment\Gateway\Blackstone\Play;

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
     * @param int    $amount
     */
    public function delete(
        string $id,
        int $amount
    ) {
        try {
            $this->refundCharge->refund(
                $id,
                $amount
            );
        } catch (Gateway\UnknownException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}