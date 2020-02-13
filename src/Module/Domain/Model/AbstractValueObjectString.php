<?php

namespace DoSystem\Module\Domain\Model;

abstract class AbstractValueObjectString
{
    use ValueObjectTrait;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $minLength;

    /**
     * @var int
     */
    protected $maxLength;

    /**
     * The regexp pattern for string
     *
     * @var string
     */
    protected $pattern;

    /**
     * @var bool
     */
    protected $multibyte = true;

    /**
     * Constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new \Exception();
        }
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value): bool
    {
        //

        return true;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
