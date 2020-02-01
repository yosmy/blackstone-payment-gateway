<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;
use Yosmy\ReportError;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.execute_charge']
 * })
 */
class ExecuteCharge implements Gateway\ExecuteCharge
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @var Gateway\ProcessApiException[]
     */
    private $processExceptionServices;

    /**
     * @var ReportError
     */
    private $reportError;

    /**
     * @di\arguments({
     *     processExceptionServices: '#yosmy.payment.gateway.blackstone.execute_charge.exception_throwed',
     * })
     *
     * @param ExecuteRequest                $executeRequest
     * @param Gateway\ProcessApiException[] $processExceptionServices
     * @param ReportError                   $reportError
     */
    public function __construct(
        ExecuteRequest $executeRequest,
        array $processExceptionServices,
        ReportError $reportError
    ) {
        $this->executeRequest = $executeRequest;
        $this->processExceptionServices = $processExceptionServices;
        $this->reportError = $reportError;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(
        string $customer,
        string $card,
        int $amount,
        string $description,
        string $statement
    ): Gateway\Charge {
        $amount = number_format($amount / 100, 2, '.', '');

        try {
            $response = $this->executeRequest->execute(
                '/api/Transactions/SaleWithToken',
                [
                    'Amount' => $amount,
                    'clientRef' => $customer,
                    'Comments' => $description,
                    'Token' => $card,
                    'TransactionType' => 2, // CREDIT_WITH_TOKEN
                    'UserTransactionNumber' => uniqid()
                ]
            );
        } catch (Gateway\ApiException $e) {
            foreach ($this->processExceptionServices as $service) {
                try {
                    $service->process($e);
                } catch (Gateway\FundsException|Gateway\IssuerException|Gateway\RiskException|Gateway\FraudException $e) {
                    throw $e;
                } catch (Gateway\FieldException $e) {
                    // Sometimes, field exception occurs while executing charge
                    throw $e;
                }
            }

            $this->reportError->report($e);

            throw new Gateway\UnknownException();
        }

        $id = $response['ServiceReferenceNumber'];

        return new Gateway\Charge(
            $id,
            time()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function identify(): string
    {
        return 'blackstone';
    }
}
