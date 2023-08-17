<?php

namespace App\Tests;

use App\Entity\Product;

class ProductsTest extends DatabaseDependantTastCase
{
    /** @test */
    public function a_product_can_be_created()
    {
        // SETUP
        $name = 'Roland TD-07KV V-Drum Electronic Drum Kit BUNDLE';
        $description = 'If you don’t want to preface the docker command with sudo, create a Unix group called docker and add users to it. When the Docker daemon starts, it creates a Unix socket accessible by members of the docker group. On some Linux distributions, the system automatically creates this group when installing Docker Engine using a package manager. In that case, there is no need for you to manually create the group.';

        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice(94400);

        // DO SOMETHING
        $this->entityManager->persist($product);
        $this->entityManager->flush();

        //MAKE ASSERTIONS
        $this->assertSame(1, $product->getId());

        $this->assertDatabaseHas(\App\Entity\Product::class, [
            'name' => $name,
            'description' => $description,
        ]);

        $this->assertDatabaseNotHas(\App\Entity\Product::class, [
            'name' => $name,
            'description' => 'foobar',
        ]);
    }
}