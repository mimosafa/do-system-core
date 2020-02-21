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
     * @var bool
     */
    protected $allowEmpty = true;

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
        if (!\is_string($value)) {
            return false;
        }
        if (!$this->allowEmpty && !$value) {
            return false;
        }
        if (!$this->multibyte && (\strlen($value) !== \mb_strlen($value))) {
            return false;
        }
        if ($this->pattern && !\preg_match($this->pattern, $value)) {
            return false;
        }
        if (isset($this->minLength) && $this->strlen($value) < $this->minLength) {
            return false;
        }
        if (isset($this->maxLength) && $this->strlen($value) > $this->maxLength) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
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

    /**
     * @param string $str
     * @return int
     */
    protected function strlen(string $str) {
        return $this->multibyte ? \mb_strlen($str) : \strlen($str);
    }
}
