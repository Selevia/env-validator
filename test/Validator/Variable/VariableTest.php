<?php


namespace Selevia\EnvValidatorTest\Validator\Variable;

use Selevia\EnvValidator\Validator\Variable\Variable;
use PHPUnit\Framework\TestCase;

class VariableTest extends TestCase
{

    /**
     * @test
     *
     * @dataProvider notEmptyVariableProvider
     *
     * @param string $name
     * @param string $value
     */
    public function isEmptyReturnsFalseIfValueIsNotEmpty(string $name, string $value): void
    {
        $variable = new Variable($name, $value);

        $this->assertFalse($variable->isEmpty());
    }

    /**
     * @test
     *
     * @dataProvider emptyVariableProvider
     *
     * @param string $name
     * @param string $value
     */
    public function isEmptyReturnsTrueIfValueIsEmpty(string $name, string $value): void
    {
        $variable = new Variable($name, $value);

        $this->assertTrue($variable->isEmpty());
    }

    public function notEmptyVariableProvider(): array
    {
        return [
            ['key_1', 'not empty'],
            ['key_2', '  not empty '],
            ['', 'another not empty value']
        ];
    }

    public function emptyVariableProvider(): array
    {
        return [
            ['key_1', ''],
            ['key_2', '  '],
            ['', '']
        ];
    }
}
