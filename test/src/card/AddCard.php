<?php

namespace Yosmy\Payment\Gateway\Blackstone\Test;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Blackstone;
use LogicException;

/**
 * @di\service()
 */
class AddCard
{
    /**
     * @var Blackstone\AddCard
     */
    private $addCard;

    /**
     * @param Blackstone\AddCard $addCard
     */
    public function __construct(Blackstone\AddCard $addCard)
    {
        $this->addCard = $addCard;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/blackstone/add-card"})
     *
     * @param string $customer
     * @param string $number
     * @param string $month
     * @param string $year
     * @param string $cvc
     *
     * @return Gateway\Card
     */
    public function add(
        string $customer,
        string $number,
        string $month,
        string $year,
        string $cvc
    ) {
        try {
            return $this->addCard->add(
                $customer,
                $number,
                $month,
                $year,
                $cvc
            );
        } catch (Gateway\FieldException $e) {
            throw new LogicException(null, null, $e);
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