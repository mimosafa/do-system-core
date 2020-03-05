<?php

namespace DoSystem\Domain\Municipality\Model;

interface MunicipalityRepositoryInterface
{
    /**
     * @param MunicipalityValueId $id
     * @return Municipality
     */
    public function findById(MunicipalityValueId $id): Municipality;
}
