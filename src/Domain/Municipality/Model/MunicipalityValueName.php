<?php

namespace DoSystem\Domain\Municipality\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

final class MunicipalityValueName implements ValueObjectInterface
{
    use ValueObjectTrait;

    /**
     * Name of '市区郡'
     *
     * @var string
     */
    private $countryOrCityName;

    /**
     * Name of '区町村'
     *
     * @var string
     */
    private $townOrWardName;

    /**
     * Constructor
     *
     * @param string $countryOrCityName
     * @param string $townOrWardName
     */
    public function __construct(string $countryOrCityName, string $townOrWardName = '')
    {
        if (!$countryOrCityName) {
            throw new \Exception();
        }
        $this->countryOrCityName = $countryOrCityName;
        $this->townOrWardName = $townOrWardName;
    }

    /**
     * @param bool $full
     * @return string
     */
    public function getValue(bool $full = false): string
    {
        $value = $this->getCountryOrCityName();
        if (!$value || $full) {
            $value .= $this->getTownOrWardName();
        }
        return $value;
    }

    /**
     * @return string
     */
    public function getCountryOrCityName(): string
    {
        return $this->countryOrCityName;
    }

    /**
     * @return string
     */
    public function getTownOrWardName(): string
    {
        return $this->townOrWardName;
    }

    /**
     * @param mixed $valueObject
     * @return bool
     */
    public function equals($valueObject): bool
    {
        return $valueObject instanceof static
            && $this->getCountryOrCityName() === $valueObject->getCountryOrCityName()
            && $this->getTownOrWardName() === $valueObject->getTownOrWardName();
    }
}
