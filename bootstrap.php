<?php
// bootstrap.php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Attributes
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__."/src"),
    isDevMode: true,
);

// configuring the database connection
$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'user' => 'alex',
    'password' => 'alex1970AbCd!MD2030ZX52',
    'dbname' => 'ecommerce',
    'host' => 'localhost',
], $config);

// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);
