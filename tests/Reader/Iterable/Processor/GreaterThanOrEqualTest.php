<?php

declare(strict_types=1);

namespace Yiisoft\Data\Tests\Reader\Iterable\Processor;

use DateTimeImmutable;
use InvalidArgumentException;
use Yiisoft\Data\Reader\FilterDataValidationHelper;
use Yiisoft\Data\Reader\Iterable\Processor\GreaterThanOrEqual;
use Yiisoft\Data\Tests\TestCase;

final class GreaterThanOrEqualTest extends TestCase
{
    public function matchScalarDataProvider(): array
    {
        return [
            [true, ['value', 44]],
            [true, ['value', 45]],
            [true, ['value', '45']],
            [false, ['value', 46]],
            [false, ['not-exist', 44]],
        ];
    }

    /**
     * @dataProvider matchScalarDataProvider
     */
    public function testMatchScalar(bool $expected, array $arguments): void
    {
        $processor = new GreaterThanOrEqual();

        $item = [
            'id' => 1,
            'value' => 45,
        ];

        $this->assertSame($expected, $processor->match($item, $arguments, []));
    }

    public function matchDateTimeInterfaceDataProvider(): array
    {
        return [
            [true, ['value', new DateTimeImmutable('2022-02-22 16:00:44')]],
            [true, ['value', new DateTimeImmutable('2022-02-22 16:00:45')]],
            [false, ['value', new DateTimeImmutable('2022-02-22 16:00:46')]],
            [false, ['not-exist', new DateTimeImmutable('2022-02-22 16:00:44')]],
        ];
    }

    /**
     * @dataProvider matchDateTimeInterfaceDataProvider
     */
    public function testMatchDateTimeInterface(bool $expected, array $arguments): void
    {
        $processor = new GreaterThanOrEqual();

        $item = [
            'id' => 1,
            'value' => new DateTimeImmutable('2022-02-22 16:00:45'),
        ];

        $this->assertSame($expected, $processor->match($item, $arguments, []));
    }

    public function invalidCountArgumentsDataProvider(): array
    {
        return [
            'zero' => [[]],
            'one' => [[1]],
            'three' => [[1, 2, 3]],
            'four' => [[1, 2, 3, 4]],
        ];
    }

    /**
     * @dataProvider invalidCountArgumentsDataProvider
     */
    public function testMatchFailForInvalidCountArguments($arguments): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$arguments should contain exactly two elements.');

        (new GreaterThanOrEqual())->match(['id' => 1], $arguments, []);
    }

    /**
     * @dataProvider invalidStringValueDataProvider
     */
    public function testMatchFailForInvalidFieldValue($field): void
    {
        $type = FilterDataValidationHelper::getValueType($field);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The field should be string. The $type is received.");

        (new GreaterThanOrEqual())->match(['id' => 1], [$field, 1], []);
    }
}
