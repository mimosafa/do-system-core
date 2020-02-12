<?php

namespace DoSystem\Module\Domain\Model;

abstract class AbstractValueObjectIntIncrementedForIdentification
{
    use ValueObjectTrait;

    /**
     * Id value for model unstored to storage.
     * Used by usecase storing new model.
     */
    protected const UNSTORED_ENTITY_MODEL_ID = -1;

    /**
     * @var int
     */
    protected $value;

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
    public function exists(): bool
    {
        return $this->value !== self::UNSTORED_ENTITY_MODEL_ID;
    }
}
