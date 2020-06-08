<?php


namespace Selevia\EnvValidator\Validator\Variable;


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

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }


}