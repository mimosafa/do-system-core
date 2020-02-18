<?php

use DoSystem\Application;
use DoSystem\Domain;
use DoSystemMock\Application as MockApplication;
use DoSystemMock\InMemoryInfrastructure as MockInMemoryInfrastructure;

doSystem()->singleton(Domain\Car\Model\CarRepositoryInterface::class, MockInMemoryInfrastructure\Repository\CarRepository::class);
doSystem()->singleton(Domain\Vendor\Model\VendorRepositoryInterface::class, MockInMemoryInfrastructure\Repository\VendorRepository::class);

doSystem()->bind(Application\Vendor\Data\CreateVendorInputInterface::class, MockApplication\Vendor\Data\CreateVendorInputMock::class);
doSystem()->bind(Application\Vendor\Data\GetVendorOutputInterface::class, MockApplication\Vendor\Data\GetVendorOutputMock::class);
