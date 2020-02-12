<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;
use DoSystem\Module\Domain\Model\ValueObjectTrait;

final class VendorValueId implements ValueObjectInterface
{
    use ValueObjectTrait;

    /**
     * Id value for model unstored to storage.
     * Used by usecase storing new model.
     */
    private const UNSTORED_ENTITY_MODEL_ID = -1;

    /**
     * @var int
     */
    private $value;

    /**
     * Constructor
     *
     * @param int|null $value
     */
    public function __construct(?int $value = null)
    {
        if ($value === null) {
            $value = self::UNSTORED_ENTITY_MODEL_ID;
        }
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isPersisted(): bool
    {
        return $this->value !== self::UNSTORED_ENTITY_MODEL_ID;
    }
}
