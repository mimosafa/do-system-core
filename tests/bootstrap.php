<?php

use DoSystem\Application;
use DoSystem\Domain;
use DoSystemMock\Application as MockApplication;
use DoSystemMock\InMemoryInfrastructure as MockInMemoryInfrastructure;

/**
 * Repositories
 */
doSystem()->singleton(Domain\Car\Model\CarRepositoryInterface::class, MockInMemoryInfrastructure\Repository\CarRepositoryMock::class);
doSystem()->singleton(Domain\Vendor\Model\VendorRepositoryInterface::class, MockInMemoryInfrastructure\Repository\VendorRepositoryMock::class);

/**
 * Get service application outputs
 */
doSystem()->bind(Application\Car\Data\GetCarOutputInterface::class, MockApplication\Car\Data\GetCarOutputMock::class);
doSystem()->bind(Application\Vendor\Data\GetVendorOutputInterface::class, MockApplication\Vendor\Data\GetVendorOutputMock::class);

/**
 * Query service application filters
 */
doSystem()->bind(Application\Vendor\Data\QueryVendorFilterInterface::class, MockApplication\Vendor\Data\QueryVendorFilterMock::class);

/**
 * Query service application outputs
 */
doSystem()->bind(Application\Car\Data\QueriedCarOutputInterface::class, MockApplication\Car\Data\QueriedCarOutputMock::class);
doSystem()->bind(Application\Vendor\Data\QueriedVendorOutputInterface::class, MockApplication\Vendor\Data\QueriedVendorOutputMock::class);
