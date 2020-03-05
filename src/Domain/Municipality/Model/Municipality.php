<?php

namespace DoSystem\Domain\Municipality\Model;

use DoSystem\Domain\Prefecture\Model\Prefecture;

class Municipality
{
    /**
     * @var MunicipalityValueId
     */
    private $id;

    /**
     * @var Prefecture
     */
    private $prefecture;

    /**
     * @var MunicipalityValueName
     */
    private $name;

    /**
     * Constructor
     *
     * @param MunicipalityValueId $id
     * @param Prefecture $prefecture
     * @param MunicipalityValueName $name
     */
    public function __construct(MunicipalityValueId $id, Prefecture $prefecture, MunicipalityValueName $name)
    {
        $this->id = $id;
        $this->prefecture = $prefecture;
        $this->name = $name;
    }

    /**
     * @return MunicipalityValueId
     */
    public function getId(): MunicipalityValueId
    {
        return $this->id;
    }

    /**
     * @return Prefecture
     */
    public function getPrefecture(): Prefecture
    {
        return $this->prefecture;
    }

    /**
     * @return MunicipalityValueName
     */
    public function getName(): MunicipalityValueName
    {
        return $this->name;
    }
}
