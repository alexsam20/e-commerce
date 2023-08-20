<?php

namespace App\Tests;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DatabaseDependantTastCase extends TestCase
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

    public function assertDatabaseHas(string $tablename, array $criteria)
    {
        // Get SQL placeholders (column name)
        $sqlParameters = $keys = array_keys($criteria);
        $firstColumn = array_shift($sqlParameters);

        // Create base SQL
        // SELECT 1 FROM tablename WHERE columnName = :columnName
        $sql = "SELECT 1 FROM {$tablename} WHERE {$firstColumn} = :{$firstColumn}";

        // if more then one filter needed, loop over remaining attributes
        // add them to the SQL
        foreach ($sqlParameters as $column) {
            $sql .= " AND {$column} = :{$column}";
        }

        // Create the $stmt
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);

        // Bind the values
        foreach ($keys as $key) {
            $stmt->bindValue($key, $criteria[$key]);
        }
        $keyValuesString = $this->asKeyValuesString($criteria);
        $failureMessage = "A record could not be found in the {$tablename} table with the following attributes: {$keyValuesString}";

        // Execute the query
        $result = $stmt->executeQuery();

        $this->assertTrue((bool) $result->fetchOne(), $failureMessage);
    }

    public function assertDatabaseHasEntity(string $entity, array $criteria)
    {
        $result = $this->entityManager->getRepository($entity)->findOneBy($criteria);
        $keyValuesString = $this->asKeyValuesString($criteria);
        $failureMessage = "A $entity record could not be found with the following attributes: {$keyValuesString}";

        $this->assertTrue((bool) $result, $failureMessage);
    }

    public function assertDatabaseNotHas(string $entity, array $criteria)
    {
        $result = $this->entityManager->getRepository($entity)->findOneBy($criteria);
        $keyValuesString = $this->asKeyValuesString($criteria);
        $failureMessage = "A $entity record WAS found with the following attributes: {$keyValuesString}";

        $this->assertFalse((bool) $result, $failureMessage);
    }

    public function asKeyValuesString(array $criteria, $separator = ' = ')
    {
        $mappedAttributes = array_map(function ($key, $value) use ($separator) {
            if ($value instanceof \DateTimeImmutable) {
                $value = $value->format('Y-m-d');
            }
            return $key . $separator . $value;
        }, array_keys($criteria), $criteria);

        return implode(', ', $mappedAttributes);
    }
}