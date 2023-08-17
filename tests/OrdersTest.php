<?php

namespace App\Tests;

use App\Entity\Product;

class OrdersTest extends DatabaseDependantTastCase
{
    /** @test  */
    public function an_order_can_be_created()
    {
        // SETUP
        $deliveryName = 'Ringo Starr';
        $deliveryAddress = '44 Penny Line, Liverpool';

        $order = new \App\Entity\Order();
        $order->setDeliveryName($deliveryName);
        $order->setDeliveryaddress($deliveryAddress);

        // DO SOMETHING
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        //MAKE ASSERTIONS
        //$this->assertSame(1, $product->getId());

        $this->assertDatabaseHas(\App\Entity\Order::class, [
            'deliveryName' => $deliveryName,
            'deliveryAddress' => $deliveryAddress,
        ]);
    }
}