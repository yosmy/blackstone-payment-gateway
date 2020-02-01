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
     * @var AssertCombination
     */
    private $assertCombination;

    /**
     * @var ReportError
     */
    private $reportError;

    /**
     * @param ExecuteRequest $executeRequest
     * @param AssertCombination $assertCombination
     * @param ReportError $reportError
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        AssertCombination $assertCombination,
        ReportError $reportError
    ) {
        $this->executeRequest = $executeRequest;
        $this->assertCombination = $assertCombination;
        $this->reportError = $reportError;
    }

    /**
     * {@inheritDoc}
     */
    public function refund(
        string $id,
        int $amount
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
            if ($this->assertCombination->assert(
                [
                    ['ResponseCode' => 6, 'Verbiage' => 'USE VOID OR REVERSAL TO REFUND UNSETTLED TRANSACTIONS']
                ],
                $e->getResponse()
            )) {
                try {
                    $this->executeRequest->execute(
                        '/api/Transactions/DoVoid',
                        [
                            'ServiceTransactionNumber' => $id,
                            'UserTransactionNumber' => uniqid(),
                        ]
                    );

                    return;
                } catch (Gateway\ApiException $e) {
                    $this->reportError->report($e);

                    throw new Gateway\UnknownException();
                }
            }

            $this->reportError->report($e);

            throw new Gateway\UnknownException();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function identify(): string
    {
        return 'blackstone';
    }
}
