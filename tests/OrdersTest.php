<?php

namespace App\Tests;

use App\Entity\Order;

class OrdersTest extends DatabaseDependantTastCase
{
    private string $deliveryName = 'Ringo Starr';
    private string $deliveryAddress = '44 Penny Line, Liverpool';

    protected function setUp(): void
    {
        parent::setUp();

        $order = new Order();
        $order->setDeliveryName($this->deliveryName);
        $order->setDeliveryaddress($this->deliveryAddress);

        // DO SOMETHING
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /** @test  */
    public function an_order_can_be_created()
    {
        // MAKE ASSERTIONS
        $this->assertDatabaseHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => null,
        ]);
    }

    /** @test  */
    public function an_order_can_be_updated()
    {
        // SETUP
        /** @var Order $order */
        $order =  $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
        ]);
        $newAddress = '9 Newcastle Avenue, Liverpool';

        // DO SOMETHING
        $order->setDeliveryAddress($newAddress);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // MAKE ASSERTIONS
        $this->assertDatabaseHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $newAddress,
        ]);
        $this->assertDatabaseNotHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);
    }

    /** @test  */
    public function an_order_can_be_cancelled()
    {
        // SETUP
        /** @var Order $order */
        $order =  $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
        ]);

        $cancelledAt = new \DateTimeImmutable();

        // DO SOMETHING
        $order->setCancelledAt($cancelledAt);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        // MAKE ASSERTIONS
        $this->assertDatabaseHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => $cancelledAt,
        ]);
    }
}