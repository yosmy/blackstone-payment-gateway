<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;
use Yosmy\ReportError;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.add_card']
 * })
 */
class AddCard implements Gateway\AddCard
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
     *     processExceptionServices: '#yosmy.payment.gateway.blackstone.add_card.exception_throwed',
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
    public function add(
        string $customer,
        string $number,
        string $name,
        string $month,
        string $year,
        string $cvc,
        string $zip
    ): Gateway\Card {
        try {
            $response = $this->executeRequest->execute(
                '/api/MonetraAdmin/GetTokenForCard',
                [
                    'Account' => $number,
                    'NameOnCard' => $name,
                    'expDate' => sprintf('%s%s', $month, $year),
                    'cv' => $cvc,
                    'zipCode' => '00000' // Needs to be something, even if it's disabled on config
                ]
            );
        } catch (Gateway\ApiException $e) {
            foreach ($this->processExceptionServices as $service) {
                try {
                    $service->process($e);
                } catch (Gateway\FieldException|Gateway\IssuerException|Gateway\RiskException|Gateway\FraudException $e) {
                    throw $e;
                } catch (Gateway\FundsException $e) {
                    // Why fund exception in execute charge?
                    $this->reportError->report($e);

                    throw new Gateway\UnknownException();
                }
            }

            $this->reportError->report($e);

            throw new Gateway\UnknownException();
        }

        $last4 = substr($number, -4);

        return new Gateway\Card(
            $response['Token'],
            $last4
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