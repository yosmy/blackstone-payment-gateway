<?php

namespace Yosmy\Payment\Gateway\Blackstone\Play;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Blackstone;
use LogicException;

/**
 * @di\service()
 */
class ExecuteCharge
{
    /**
     * @var Blackstone\ExecuteCharge
     */
    private $executeCharge;

    /**
     * @param Blackstone\ExecuteCharge $executeCharge
     */
    public function __construct(Blackstone\ExecuteCharge $executeCharge)
    {
        $this->executeCharge = $executeCharge;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/blackstone/execute-charge"})
     *
     * @param string $customer
     * @param string $card
     * @param int    $amount
     * @param string $description
     * @param string $statement
     */
    public function delete(
        string $customer,
        string $card,
        int $amount,
        string $description,
        string $statement
    ) {
        try {
            $this->executeCharge->execute(
                $customer,
                $card,
                $amount,
                $description,
                $statement
            );
        } catch (Gateway\FraudException $e) {
            throw new LogicException(null, null, $e);
        } catch (Gateway\FundsException $e) {
            throw new LogicException(null, null, $e);
        } catch (Gateway\IssuerException $e) {
            throw new LogicException(null, null, $e);
        } catch (Gateway\RiskException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}