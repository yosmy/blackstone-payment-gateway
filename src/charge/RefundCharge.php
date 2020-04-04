<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;
use Yosmy\ReportError;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.refund_charge']
 * })
 */
class RefundCharge implements Gateway\RefundCharge
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @var ReportError
     */
    private $reportError;

    /**
     * @param ExecuteRequest $executeRequest
     * @param ReportError    $reportError
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        ReportError $reportError
    ) {
        $this->executeRequest = $executeRequest;
        $this->reportError = $reportError;
    }

    /**
     * {@inheritDoc}
     */
    public function refund(
        string $id,
        ?int $amount
    ) {
        $amount = number_format($amount / 100, 2, '.', '');

        try {
            $this->executeRequest->execute(
                '/api/Transactions/DoRefund',
                [
                    'ServiceTransactionNumber' => $id,
                    'Amount' => $amount,
                    'UserTransactionNumber' => uniqid(),
                ]
            );
        } catch (Gateway\ApiException $e) {
            $this->reportError->report($e);

            throw new Gateway\UnknownException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function identify()
    {
        return 'blackstone';
    }
}
