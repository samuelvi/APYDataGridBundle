<?php

namespace APY\DataGridBundle\Tests\Grid\Column;

use APY\DataGridBundle\Grid\Column\Column;
use APY\DataGridBundle\Grid\Column\TextColumn;
use APY\DataGridBundle\Grid\Filter;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class TextColumnTest extends TestCase
{
    /** @var TextColumn */
    private $column;

    public function testGetType()
    {
        $this->assertEquals('text', $this->column->getType());
    }

    public function testIsQueryValid()
    {
        $this->assertTrue($this->column->isQueryValid('foo'));
        $this->assertTrue($this->column->isQueryValid(['foo', 1, 'bar', null]));
        $this->assertFalse($this->column->isQueryValid(1));
    }

    public function testNullOperatorFilters()
    {
        $this->column->setData(['operator' => Column::OPERATOR_ISNULL]);
        $this->assertEquals([
            new Filter(Column::OPERATOR_ISNULL),
            new Filter(Column::OPERATOR_EQ, ''),
        ], $this->column->getFilters('asource'));
        $this->assertAttributeEquals(Column::DATA_DISJUNCTION, 'dataJunction', $this->column);
    }

    public function testNotNullOperatorFilters()
    {
        $this->column->setData(['operator' => Column::OPERATOR_ISNOTNULL]);
        $this->assertEquals([
            new Filter(Column::OPERATOR_ISNOTNULL),
            new Filter(Column::OPERATOR_NEQ, ''),
        ], $this->column->getFilters('asource'));
    }

    public function testOtherOperatorFilters()
    {
        $operators = [
            Column::OPERATOR_EQ,
            Column::OPERATOR_NEQ,
            Column::OPERATOR_LT,
            Column::OPERATOR_LTE,
            Column::OPERATOR_GT,
            Column::OPERATOR_GTE,
            Column::OPERATOR_BTW,
            Column::OPERATOR_BTWE,
            Column::OPERATOR_LIKE,
            Column::OPERATOR_NLIKE,
            Column::OPERATOR_RLIKE,
            Column::OPERATOR_LLIKE,
            Column::OPERATOR_SLIKE,
            Column::OPERATOR_NSLIKE,
            Column::OPERATOR_RSLIKE,
            Column::OPERATOR_LSLIKE,
        ];
        foreach ($operators as $operator) {
            $this->column->setData(['operator' => $operator]);
            $this->assertEmpty($this->column->getFilters('asource'));
        }
    }

    public function setUp()
    {
        $this->column = new TextColumn();
    }
}
