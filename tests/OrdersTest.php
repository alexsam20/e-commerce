<?php

namespace App\Tests;

use App\Entity\Order;
use App\Entity\Product;

class OrdersTest extends DatabaseDependantTastCase
{
    private string $deliveryName = 'Ringo Starr';
    private string $deliveryAddress = '44 Penny Line, Liverpool';

    protected function setUp(): void
    {
        parent::setUp();

        $order = new \App\Entity\Order();
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
        $this->assertDatabaseHas(\App\Entity\Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
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
        $this->assertDatabaseHas(\App\Entity\Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $newAddress,
        ]);
        $this->assertDatabaseNotHas(\App\Entity\Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);
    }
}