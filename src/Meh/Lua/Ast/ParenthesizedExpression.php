<?php
namespace Meh\Lua\Ast;

class ParenthesizedExpression implements PrefixExpression
{
    /** @var Expression */
    public $expression;

    /** @param Expression $expression */
    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }
}
