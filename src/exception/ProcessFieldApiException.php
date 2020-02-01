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
class ProcessFieldApiException implements Gateway\ProcessApiException
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
                ['ResponseCode' => 3, 'Verbiage' => 'INVALID CARD #'],
                ['ResponseCode' => 3, 'Verbiage' => 'EXPIRED CARD'],
                ['ResponseCode' => 25, 'Verbiage' => 'LUHN/MOD10 CHECK ON ACCOUNT NUMBER FAILED'],
                ['ResponseCode' => 25, 'Verbiage' => 'UNABLE TO DETERMINE CARDTYPE'],
            ],
            $response
        )) {
            throw new Gateway\FieldException(Gateway\FieldException::FIELD_NUMBER);
        }
        else if ($this->assertCombination->assert(
            [
                ['ResponseCode' => 3, 'Verbiage' => 'EXPIRATION DATE MUST BE IN FUTURE'],
                ['ResponseCode' => 3, 'Verbiage' => 'INVALID EXP DATE'],
                ['ResponseCode' => 25, 'Verbiage' => 'INVALID EXPDATE'],
            ],
            $response
        )) {
            throw new Gateway\FieldException(Gateway\FieldException::FIELD_EXPIRY);
        }
        else if ($this->assertCombination->assert(
            [
                ['ResponseCode' => 25, 'Verbiage' => 'INVALID ZIPCODE. NOT VALID FOR US OR CANADA'],
            ],
            $response
        )) {
            throw new Gateway\FieldException(Gateway\FieldException::FIELD_ZIP);
        }
    }
}