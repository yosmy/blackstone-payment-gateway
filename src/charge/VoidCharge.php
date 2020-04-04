<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;
use Yosmy\ReportError;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.void_charge']
 * })
 */
class VoidCharge implements Gateway\VoidCharge
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
    public function void(
        string $id
    ) {
        try {
            $this->executeRequest->execute(
                '/api/Transactions/DoVoid',
                [
                    'ServiceTransactionNumber' => $id,
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
