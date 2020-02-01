<?php

namespace Yosmy\Payment\Gateway\Blackstone\Play;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Blackstone;
use LogicException;

/**
 * @di\service()
 */
class DeleteCard
{
    /**
     * @var Blackstone\DeleteCard
     */
    private $deleteCard;

    /**
     * @param Blackstone\DeleteCard $deleteCard
     */
    public function __construct(Blackstone\DeleteCard $deleteCard)
    {
        $this->deleteCard = $deleteCard;
    }

    /**
     * @cli\resolution({command: "/payment/gateway/blackstone/delete-card"})
     *
     * @param string $customer
     * @param string $card
     */
    public function delete(
        string $customer,
        string $card
    ) {
        try {
            $this->deleteCard->delete(
                $customer,
                $card
            );
        } catch (Gateway\UnknownException $e) {
            throw new LogicException(null, null, $e);
        }
    }
}