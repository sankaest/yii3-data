<?php

declare(strict_types=1);

namespace Yiisoft\Data\Tests\Reader\Filter;

use DateTimeInterface;
use InvalidArgumentException;
use Yiisoft\Data\Reader\Filter\Equals;
use Yiisoft\Data\Reader\FilterDataValidationHelper;
use Yiisoft\Data\Tests\TestCase;

use function sprintf;

final class EqualsTest extends TestCase
{
    /**
     * @dataProvider scalarAndDataTimeInterfaceValueDataProvider
     */
    public function testToArray($value): void
    {
        $filter = new Equals('test', $value);

        $this->assertSame(['=', 'test', $value], $filter->toArray());
    }

    /**
     * @dataProvider invalidScalarValueDataProvider
     */
    public function testConstructorFailForInvalidValue($value): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage(sprintf(
            'The value should be scalar or %s instance. The %s is received.',
            DateTimeInterface::class,
            FilterDataValidationHelper::getValueType($value),
        ));

        new Equals('test', $value);
    }
}
