<?php

use DoSystem\Application;
use DoSystem\Domain;
use DoSystem\Infrastructure;
use DoSystemMock\Application as MockApplication;
use DoSystemMock\Infrastructure as MockInfrastructure;

define('DOSYSTEM_TESTS_ROOT_DIR', __DIR__);

/**
 * Repositories
 */
doSystem()->singleton(
    Domain\Car\Model\CarRepositoryInterface::class,
    MockInfrastructure\Repository\CarRepositoryMock::class
);
doSystem()->singleton(
    Domain\Prefecture\Model\PrefectureRepositoryInterface::class,
    Infrastructure\Repository\PrefectureRepository::class
);
doSystem()->singleton(
    Domain\Vendor\Model\VendorRepositoryInterface::class,
    MockInfrastructure\Repository\VendorRepositoryMock::class
);

/**
 * Get service application outputs
 */
doSystem()->bind(
    Application\Brand\Data\GetBrandOutputInterface::class,
    MockApplication\Brand\Data\GetBrandOutputMock::class
);
doSystem()->bind(
   Application\Car\Data\GetCarOutputInterface::class,
   MockApplication\Car\Data\GetCarOutputMock::class
);
doSystem()->bind(
    Application\Vendor\Data\GetVendorOutputInterface::class,
    MockApplication\Vendor\Data\GetVendorOutputMock::class
);

/**
 * Query service application filters
 */
doSystem()->bind(
    Application\Car\Data\QueryCarFilterInterface::class,
    MockApplication\Car\Data\QueryCarFilterMock::class
);
doSystem()->bind(
    Application\Vendor\Data\QueryVendorFilterInterface::class,
    MockApplication\Vendor\Data\QueryVendorFilterMock::class
);

/**
 * Query service application outputs
 */
doSystem()->bind(
    Application\Car\Data\QueriedCarOutputInterface::class,
    MockApplication\Car\Data\QueriedCarOutputMock::class
);
doSystem()->bind(
    Application\Vendor\Data\QueriedVendorOutputInterface::class,
    MockApplication\Vendor\Data\QueriedVendorOutputMock::class
);

/**
 * Update service application outputs
 */
doSystem()->bind(
    Application\Brand\Data\UpdateBrandOutputInterface::class,
    MockApplication\Brand\Data\UpdateBrandOutputMock::class
);
doSystem()->bind(
   Application\Car\Data\UpdateCarOutputInterface::class,
   MockApplication\Car\Data\UpdateCarOutputMock::class
);
doSystem()->bind(
   Application\Vendor\Data\UpdateVendorOutputInterface::class,
   MockApplication\Vendor\Data\UpdateVendorOutputMock::class
);
