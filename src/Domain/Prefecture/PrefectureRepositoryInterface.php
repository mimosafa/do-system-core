<?php

namespace DoSystem\Core\Domain\Prefecture;

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
