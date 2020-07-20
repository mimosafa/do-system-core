<?php

namespace DoSystem\Core\Domain\Prefecture;

class Prefecture
{
    /**
     * @var PrefectureValueId
     */
    private $id;

    /**
     * @var PrefectureValueName
     */
    private $name;

    /**
     * Constructor
     *
     * @param PrefectureValueId $id
     * @param PrefectureValueName $name
     */
    public function __construct(PrefectureValueId $id, PrefectureValueName $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return PrefectureValueId
     */
    public function getId(): PrefectureValueId
    {
        return $this->id;
    }

    /**
     * @return PrefectureValueName
     */
    public function getName(): PrefectureValueName
    {
        return $this->name;
    }
}
