<?php

declare(strict_types=1);

use Costa\Entity\Contracts\DataInterface;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;
use Tests\Stubs\AddressStub;
use Tests\Stubs\CustomerStub;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

describe("CustomerStub Unit Test", function () {
    it("Creating a new customer", function () {
        $customer = new CustomerStub(name: 'testing');
        assertInstanceOf(DataInterface::class, $customer);
    });

    it("Creating a customer with data", function () {
        $customer = CustomerStub::make(
            name: 'testing',
            id: $id = Uuid::make(),
            createdAt: $createdAt = new DateTime('2020-01-01 00:00:00'),
            updatedAt: $updatedAt = new DateTime('2020-01-02 00:00:00'),
        );
        assertInstanceOf(DataInterface::class, $customer);
        assertEquals($customer->id(), $id);
        assertEquals($customer->createdAt(), $createdAt->format('Y-m-d H:i:s'));
        assertEquals($customer->updatedAt(), $updatedAt->format('Y-m-d H:i:s'));
    });

    it("Creating a customer and return toArray without address and order", function () {
        $customer = CustomerStub::make(
            name: 'testing',
            id: $id = Uuid::make(),
            createdAt: $createdAt = new DateTime('2020-01-01 00:00:00'),
            updatedAt: $updatedAt = new DateTime('2020-01-02 00:00:00'),
        );

        assertEquals([
            'id' => (string)$id,
            'createdAt' => $createdAt->format('Y-m-d H:i:s'),
            'name' => (string)'testing',
            'updatedAt' => $updatedAt->format('Y-m-d H:i:s'),
            'address' => [],
            'orders' => [],
        ], $customer->toArray());
    });

    it("Creating a customer and return toArray with addresses", function () {
        $address01 = new AddressStub(name: 'testing');
        $address02 = new AddressStub(name: 'testing');
        $address03 = new AddressStub(name: 'testing');

        $customer = CustomerStub::make(
            name: 'testing',
            id: $id = Uuid::make(),
            createdAt: $createdAt = new DateTime('2020-01-01 00:00:00'),
            updatedAt: $updatedAt = new DateTime('2020-01-02 00:00:00'),
            address: [$address01, $address02, $address03]
        );

        assertEquals([
            'id' => (string)$id,
            'createdAt' => $createdAt->format('Y-m-d H:i:s'),
            'name' => (string)'testing',
            'updatedAt' => $updatedAt->format('Y-m-d H:i:s'),
            'address' => [$address01->toArray(), $address02->toArray(), $address03->toArray()],
            'orders' => [],
        ], $customer->toArray());
    });

    it("Creating a customer and return toArray with orders", function () {
        $order01 = Uuid::make();
        $order02 = Uuid::make();
        $order03 = Uuid::make();

        $customer = CustomerStub::make(
            name: 'testing',
            id: $id = Uuid::make(),
            createdAt: $createdAt = new DateTime('2020-01-01 00:00:00'),
            updatedAt: $updatedAt = new DateTime('2020-01-02 00:00:00'),
            orders: [$order01, $order02, $order03]
        );

        assertEquals([
            'id' => (string)$id,
            'createdAt' => $createdAt->format('Y-m-d H:i:s'),
            'name' => (string)'testing',
            'updatedAt' => $updatedAt->format('Y-m-d H:i:s'),
            'orders' => [(string)$order01, (string)$order02, (string)$order03],
            'address' => [],
        ], $customer->toArray());
    });

    it("Exception name of customer", fn() => expect(fn() => new CustomerStub(name: 'a'))
        ->toThrow(NotificationException::class)
    );

    it("Creating a customer and get name", function () {
        $customer = new CustomerStub(name: 'testing');
        assertEquals($customer->name, 'testing');
    });
});