<?php
namespace Meh\Lua\Ast;

class BinaryExpression implements Expression
{
    /** @var Expression */
    public $left;

    /** @var BinaryOperator */
    public $operator;

    /** @var Expression */
    public $right;

    /**
     * @param Expression $left
     * @param BinaryOperator $operator
     * @param Expression $right
     */
    public function __construct(Expression $left, BinaryOperator $operator, Expression $right)
    {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }
}
