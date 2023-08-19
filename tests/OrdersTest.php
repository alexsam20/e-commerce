<?php

namespace App\Tests;

use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Product;

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
    public function an_order_can_be_created(): void
    {
        // MAKE ASSERTIONS
        $this->assertDatabaseHas(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => null,
        ]);
    }

    /** @test  */
    public function an_order_can_be_updated(): void
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
    public function an_order_can_be_cancelled(): void
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

    /** @test  */
    public function an_item_can_be_added_to_an_order()
    {
        // SETUP
        // Need a product
        $name = 'Roland TD-07KV V-Drum Electronic Drum Kit BUNDLE';
        $description = 'If you don’t want to preface the docker command with sudo, create a Unix group called docker and add users to it. When the Docker daemon starts, it creates a Unix socket accessible by members of the docker group.';

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice(94400);
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        // Need an order
        /** @var Order $order */
        $order =  $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
        ]);

        // DO SOMETHING
        // Create an item using refs to the order and product
        $item = new Item();
        $item->setOrder($order);
        $item->setProduct($product);
        $item->setPrice($product->getPrice());

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        // MAKE ASSERTIONS
        // Check that the item has been created
        $this->assertDatabaseHas(Item::class, [
            'price' => $product->getPrice(),
        ]);
    }
}