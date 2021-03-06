<?php


namespace Selevia\EnvValidator\Validator\Variable;


use Assert\Assertion;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;

class VariableSet
{

    /**
     * @var Collection
     */
    protected $variables;

    /**
     * VariableSet constructor.
     *
     * @param Collection $variables
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Collection $variables)
    {
        $this->setVariables($variables);
    }

    /**
     * Returns true only if the variable set isn't empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->getVariables()->isEmpty();
    }

    /**
     * Returns true if the Variable with the name of the provided Variable is in the set
     *
     * @param string $variableName
     *
     * @return bool
     */
    public function contains(string $variableName): bool
    {
        return $this->getVariables()->exists(
            static function ($key, Variable $existingVariable) use ($variableName): bool {
                return $existingVariable->getName() === $variableName;
            }
        );
    }

    /**
     * Returns the intersecting VendorSet of the current VendorSet and the provided one
     *
     * @param static $anotherSet
     *
     * @return VariableSet
     */
    public function intersect(self $anotherSet): self
    {
        return new static(
            $this->getVariables()->filter(
                static function (Variable $variable) use ($anotherSet): bool {
                    return $anotherSet->contains($variable->getName());
                }
            )
        );
    }

    /**
     * Performs set subtraction with the current set acting as the minuend and provided one as the subtrahend
     *
     * @param static $subtrahend a.k.a. the right operand of the subtraction, or in this case the set being subtracted
     *
     * @return static
     */
    public function subtract(self $subtrahend): self
    {
        return new static(
            $this->getVariables()->filter(
                static function (Variable $variable) use ($subtrahend): bool {
                    return !$subtrahend->contains($variable->getName());
                }
            )
        );
    }

    /**
     * Return variables as array
     *
     * @return Variable[]
     */
    public function toArray(): array
    {
        return array_values($this->getVariables()->toArray());
    }

    /**
     * @param Collection $variables
     *
     * @throws InvalidArgumentException
     */
    protected function setVariables(Collection $variables): void
    {
        Assertion::allIsInstanceOf($variables->toArray(), Variable::class);

        $this->variables = $variables;
    }

    protected function getVariables(): Collection
    {
        return $this->variables;
    }
}