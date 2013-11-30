<?php
namespace Meh\Lua\Ast;

class UnaryExpression extends Node implements Expression
{
    /** @var UnaryOperator */
    public $operator;

    /** @var Expression */
    public $expression;

    /**
     * @param UnaryOperator $operator
     * @param Expression $expression
     */
    public function __construct(UnaryOperator $operator, Expression $expression)
    {
        $this->operator = $operator;
        $this->expression = $expression;
    }
}
