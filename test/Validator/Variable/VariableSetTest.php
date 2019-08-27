<?php


namespace Selevia\Common\EnvValidatorTest\Validator\Variable;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Selevia\Common\EnvValidator\Validator\Variable\Variable;
use Selevia\Common\EnvValidator\Validator\Variable\VariableSet;
use PHPUnit\Framework\TestCase;

class VariableSetTest extends TestCase
{

    /**
     * @test
     *
     * @dataProvider invalidVariableCollectionProvider
     *
     * @param Collection $collection
     */
    public function throwsExceptionWhenTheGivenCollectionHasInvalidTypes(Collection $collection): void
    {
        // Given
        /** @param $collection */


        // Then
        $this->expectException(\InvalidArgumentException::class);


        // When
        new  VariableSet($collection);
    }

    /**
     * @test
     */
    public function emptyVariableSetIsEmpty(): void
    {
        // Given
        $emptyVariableSet = new VariableSet(new ArrayCollection());

        // When
        $isVariableSetWithNoVariablesEmpty = $emptyVariableSet->isEmpty();

        // Then
        $this->assertTrue($isVariableSetWithNoVariablesEmpty);
    }

    /**
     * @test
     */
    public function noneEmptyVariableSetIsNotEmpty(): void
    {
        // Given
        $noneEmptyVariableSet = new VariableSet(new ArrayCollection([$this->createMock(Variable::class)]));

        // When
        $isVariableSetEmpty = $noneEmptyVariableSet->isEmpty();

        // Then
        $this->assertFalse($isVariableSetEmpty);
    }

    /**
     * @test
     */
    public function containsVariablesWithinVariableSet(): void
    {
        // Given
        $someVariables = [
            new Variable('name1', 'value1'),
            new Variable('name2', 'value2'),
            new Variable('name3', 'value3'),
        ];

        $variableSetThatContainsSomeVariables = new VariableSet(new ArrayCollection($someVariables));

        // When
        $doesItContain = $variableSetThatContainsSomeVariables->contains(reset($someVariables));

        // Then
        $this->assertTrue($doesItContain);
    }

    /**
     * @test
     */
    public function doesntContainsVariableOutsideVariableSet(): void
    {
        // Given
        $someVariables = [
            new Variable('name1', 'value1'),
            new Variable('name2', 'value2'),
            new Variable('name3', 'value3'),
        ];

        $variableSetThatContainsSomeVariables = new VariableSet(new ArrayCollection($someVariables));
        $emptyVariableSet = new VariableSet(new ArrayCollection());


        // When
        $anotherVariable = new Variable('other', 'some value');

        $doesNonEmptySetContainAnotherVariable = $variableSetThatContainsSomeVariables->contains($anotherVariable);
        $doesEmptySetContainAVariable = $emptyVariableSet->contains($anotherVariable);

        // Then
        $this->assertFalse($doesNonEmptySetContainAnotherVariable);
        $this->assertFalse($doesEmptySetContainAVariable);
    }

    /**
     * @test
     *
     * @dataProvider intersectionDataProvider
     *
     * @param VariableSet $firstSet
     * @param VariableSet $secondSet
     * @param VariableSet $expectedIntersection
     */
    public function intersectionIsCommutativeAndOtherwiseWorksAsExpectedWhenValuesAreSame(
        VariableSet $firstSet,
        VariableSet $secondSet,
        VariableSet $expectedIntersection
    ): void {
        // Given (params)

        // When
        $resultingIntersection = $firstSet->intersect($secondSet);
        $commutativeIntersection = $secondSet->intersect($firstSet);

        // Then
        $this->assertEquals($resultingIntersection->toArray(), $expectedIntersection->toArray());
        $this->assertEquals($commutativeIntersection->toArray(), $resultingIntersection->toArray());
    }

    /**
     * @test
     */
    public function intersectionReturnVariablesFromFirstOne(): void
    {
        // Given
        $variable = new Variable('name1', 'value1');
        $variableWithOtherValue = new Variable('name1', '');

        $firstSet = new VariableSet(new ArrayCollection([$variable]));
        $secondSet = new VariableSet(new ArrayCollection([$variableWithOtherValue]));

        // When
        $resultingIntersection = $firstSet->intersect($secondSet);

        $this->assertFalse($resultingIntersection->isEmpty());
        $this->assertEquals($variable->getValue(), $resultingIntersection->toArray()[0]->getValue());
    }

    /**
     * @test
     *
     * @dataProvider subtractionDataProvider
     *
     * @param VariableSet $minuend    a.k.a. the left operand of the subtraction
     * @param VariableSet $subtrahend a.k.a. the right operand of the subtraction
     * @param VariableSet $expectedDifference
     */
    public function subtractionWorksAsExpected(
        VariableSet $minuend,
        VariableSet $subtrahend,
        VariableSet $expectedDifference
    ): void {
        // Given (params)

        // When
        $resultingDifference = $minuend->subtract($subtrahend);

        // Then
        $this->assertEquals($expectedDifference->toArray(), $resultingDifference->toArray());
    }

    public function invalidVariableCollectionProvider(): array
    {
        return [
            [new ArrayCollection([$this->createMock(Variable::class), 'invalid'])],
            [new ArrayCollection(['invalid'])],
        ];
    }

    public function intersectionDataProvider(): array
    {
        $variableOne = new Variable('name1', 'value1');
        $variableTwo = new Variable('name2', 'value2');
        $variableThree = new Variable('name3', 'value3');

        return [
            [
                new VariableSet(new ArrayCollection([$variableOne])),
                new VariableSet(new ArrayCollection([])),
                new VariableSet(new ArrayCollection([])),
            ],
            [
                new VariableSet(new ArrayCollection([$variableOne, $variableThree])),
                new VariableSet(new ArrayCollection([$variableTwo])),
                new VariableSet(new ArrayCollection([])),
            ],
            [
                new VariableSet(new ArrayCollection([$variableTwo, $variableThree])),
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo])),
                new VariableSet(new ArrayCollection([$variableTwo])),
            ],
            [
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo, $variableThree])),
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo, $variableThree])),
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo, $variableThree])),
            ],
        ];
    }

    public function subtractionDataProvider(): array
    {
        $variableOne = new Variable('name1', 'value1');
        $variableTwo = new Variable('name2', 'value2');
        $variableThree = new Variable('name3', 'value3');

        return [
            [
                new VariableSet(new ArrayCollection([])),
                new VariableSet(new ArrayCollection([])),
                new VariableSet(new ArrayCollection([])),
            ],
            [
                new VariableSet(new ArrayCollection([])),
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo, $variableThree])),
                new VariableSet(new ArrayCollection([])),
            ],
            [
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo])),
                new VariableSet(new ArrayCollection([])),
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo])),
            ],

            [
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo, $variableThree])),
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo])),
                new VariableSet(new ArrayCollection([$variableThree])),
            ],
            [
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo, $variableThree])),
                new VariableSet(new ArrayCollection([$variableOne, $variableTwo, $variableThree])),
                new VariableSet(new ArrayCollection([])),
            ],
        ];
    }
}
