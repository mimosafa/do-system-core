<?php

namespace DoSystem\Core\Module\Domain;

abstract class AbstractValueObjectIntForId
{
    use ValueObjectTrait;

    /**
     * Id value for model unstored to storage.
     * Used by usecase storing new model.
     */
    protected const UNSTORED_ENTITY_ID = -1;

    /**
     * @var int
     */
    protected $value;

    /**
     * Constructor
     *
     * @param int|null $value  If making pseudo id for create new entity, pass the argument `null` expressly
     */
    public function __construct(?int $value)
    {
        if ($value === null) {
            $value = self::UNSTORED_ENTITY_ID;
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
    public function isPseudo(): bool
    {
        return $this->value === self::UNSTORED_ENTITY_ID;
    }

    /**
     * @param mixed $valueObject
     * @return bool
     */
    public function equals($valueObject): bool
    {
        return $valueObject instanceof static
            && $this->getValue() === $valueObject->getValue()
            && \get_called_class() === \get_class($valueObject);
    }
}
