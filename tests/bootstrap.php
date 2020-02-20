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
 * Service outputs
 */
doSystem()->bind(Application\Car\Data\GetCarOutputInterface::class, MockApplication\Car\Data\GetCarOutputMock::class);
doSystem()->bind(Application\Vendor\Data\GetVendorOutputInterface::class, MockApplication\Vendor\Data\GetVendorOutputMock::class);
doSystem()->bind(Application\Vendor\Data\QueriedVendorOutputInterface::class, MockApplication\Vendor\Data\QueriedVendorOutputMock::class);
