<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: ['yosmy.payment.gateway.delete_card']
 * })
 */
class DeleteCard implements Gateway\DeleteCard
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
    public function delete(
        string $customer,
        string $card
    ) {
        // Can't find documentation about this
    }

    /**
     * {@inheritDoc}
     */
    public function identify()
    {
        return 'blackstone';
    }
}