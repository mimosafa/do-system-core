<?php

namespace DoSystem\Domain\Prefecture\Model;

interface PrefectureRepositoryInterface
{
    /**
     * @param PrefectureValueId $id
     * @return Prefecture
     */
    public function findById(PrefectureValueId $id): Prefecture;

    /**
     * @param PrefectureValueName $name
     * @return Prefecture
     */
    public function findByName(PrefectureValueName $name): Prefecture;
}
