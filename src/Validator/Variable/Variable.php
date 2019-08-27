<?php


namespace Selevia\Common\EnvValidator\Validator\Variable;


class Variable
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * Variable constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Check if value is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return trim($this->getValue()) === '';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }


}