<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DatabaseDependantTastCasde extends TestCase
{
    /** @var EntityManager $entityManager */
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        require 'bootstrap-test.php';

        $this->entityManager = $entityManager;

        SchemaLoader::load($entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function assertDatabaseHas(string $entity, array $criteria)
    {
        $result = $this->entityManager->getRepository($entity)->findOneBy($criteria);

        $this->assertTrue((bool) $result);
    }
}