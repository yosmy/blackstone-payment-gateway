<?php

namespace Yosmy\Payment\Gateway\Blackstone;

use Yosmy\Payment\Gateway;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.payment.gateway.blackstone.add_card.exception_throwed',
 *         'yosmy.payment.gateway.blackstone.execute_charge.exception_throwed'
 *     ]
 * })
 */
class ProcessIssuerApiException implements Gateway\ProcessApiException
{
    /**
     * @var AssertCombination
     */
    private $assertCombination;

    /**
     * @param AssertCombination $assertCombination
     */
    public function __construct(AssertCombination $assertCombination)
    {
        $this->assertCombination = $assertCombination;
    }

    /**
     * {@inheritDoc}
     */
    public function process(Gateway\ApiException $e)
    {
        $response = $e->getResponse();

        if ($this->assertCombination->assert(
            [
                ['ResponseCode' => 3, 'Verbiage' => 'CALL 18003372255'],
                ['ResponseCode' => 3, 'Verbiage' => 'CALL CENTER'],
                ['ResponseCode' => 3, 'Verbiage' => 'DECLINED'],
                ['ResponseCode' => 3, 'Verbiage' => 'INV TRAN TYPE'],
                ['ResponseCode' => 3, 'Verbiage' => 'NO ROUTE/SUBACCT CONFIGURED FOR CARDTYPE OR TRANTYPE'],
                ['ResponseCode' => 25, 'Verbiage' => 'INVALID PARAMETER'],
                ['ResponseCode' => 25, 'Verbiage' => 'UNABLE TO DETERMINE CARDTYPE'],
            ],
            $response
        )) {
            throw new Gateway\IssuerException();
        }
    }
}