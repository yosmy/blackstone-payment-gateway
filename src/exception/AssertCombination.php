<?php

namespace Yosmy\Payment\Gateway\Blackstone;

/**
 * @di\service({private: true})
 */
class AssertCombination
{
    /**
     * @param array $combinations
     * @param array $response
     *
     * @return bool
     */
    public function assert(
        array $combinations,
        array $response
    ): bool {
        foreach ($combinations as $combination) {
            if (!isset($combination['ResponseCode'])) {
                continue;
            }

            if (!isset($response['ResponseCode'])) {
                continue;
            }

            if ($combination['ResponseCode'] != $response['ResponseCode']) {
                continue;
            }

            if (!isset($combination['Verbiage'])) {
                continue;
            }

            if (isset($response['verbiage'])) {
                $response['Verbiage'] = $response['verbiage'];
            }

            if (!isset($response['Verbiage'])) {
                continue;
            }

            if (strpos($response['Verbiage'], $combination['Verbiage']) === false) {
                continue;
            }

            return true;
        }

        return false;
    }
}