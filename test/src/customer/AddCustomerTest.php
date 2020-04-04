<?php

namespace Yosmy\Payment\Gateway\Blackstone\Test;

use Yosmy\Payment\Gateway;
use Yosmy\Payment\Gateway\Blackstone;
use PHPUnit\Framework\TestCase;
use LogicException;

class AddCustomerTest extends TestCase
{
    public function testAdd()
    {
        $executeRequest = $this->createStub(Blackstone\ExecuteRequest::class);

        $addCustomer = new Blackstone\AddCustomer($executeRequest);

        try {
            $customer = $addCustomer->add();
        } catch (Gateway\UnknownException $e) {
            throw new LogicException();
        }

        $this->assertInstanceOf('Gateway\Customer', $customer);
    }

    public function testIdentify()
    {
        $executeRequest = $this->createStub(Blackstone\ExecuteRequest::class);

        $addCustomer = new Blackstone\AddCustomer($executeRequest);

        $gateway = $addCustomer->identify();

        $this->assertEquals('blackstone', $gateway);
    }
}