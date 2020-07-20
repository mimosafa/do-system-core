<?php

namespace DoSystemMock\Application\Brand\Data;

use DoSystem\Core\Application\Brand\Data\UpdateBrandInputInterface;

class UpdateBrandInputMock implements UpdateBrandInputInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
