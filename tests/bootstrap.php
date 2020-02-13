<?php

namespace DoSystem;

app()->bind(Domain\Car\Model\CarRepositoryInterface::class, InMemory\Repositories\CarRepository::class);
app()->bind(Domain\Vendor\Model\VendorRepositoryInterface::class, InMemory\Repositories\VendorRepository::class);
