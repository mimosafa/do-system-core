<?php

namespace DoSystem\Core\Application\Car\Data;

use DoSystem\Core\Domain\Car\Car;

interface UpdateCarOutputInterface
{
    /**
     * Constructor
     *
     * ** Note **
     * Parameter 1 & 2 must be named '$model' & '$modified'
     * @see DoSystem\Core\Application\Car\Service\UpdateCarService::handle()
     *
     * @param Car $model
     * @param string[] $modified Class names of modified property
     */
    public function __construct(Car $model, array $modified);
}
