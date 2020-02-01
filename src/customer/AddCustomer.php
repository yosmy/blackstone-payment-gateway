<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.add_customer']
 * })
 */
class AddCustomer implements Gateway\AddCustomer
{
    /**
     * @var ExecuteRequest
     */
    private $executeRequest;

    /**
     * @param ExecuteRequest $executeRequest
     */
    public function __construct(
        ExecuteRequest $executeRequest
    ) {
        $this->executeRequest = $executeRequest;
    }

    /**
     * {@inheritDoc}
     */
    public function add()
    {
        $id = uniqid();

        return new Gateway\Customer(
            $id
        );
    }

    /**
     * {@inheritDoc}
     */
    public function identify()
    {
        return 'blackstone';
    }
}