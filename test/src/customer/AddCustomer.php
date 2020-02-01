<?php

namespace Yosmy\Payment\Gateway\Blackstone\Test;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Blackstone;
use LogicException;

/**
 * @di\service()
 */
class AddCustomer
{
    /**
     * @var Blackstone\AddCustomer
     */
    private $addCustomer;

    /**
     * @param Blackstone\AddCustomer $addCustomer
     */
    public function __construct(Blackstone\AddCustomer $addCustomer)
    {
        $this->addCustomer = $addCustomer;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/blackstone/add-customer"})
     *
     * @return Gateway\Customer
     */
    public function add() {
        try {
            return $this->addCustomer->add();
        } catch (Gateway\ApiException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}