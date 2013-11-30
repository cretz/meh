<?php
namespace Meh\Lua\Ast;

class ParenthesizedExpression extends Node implements PrefixExpression
{
    /** @var Expression */
    public $expression;

    /** @param Expression $expression */
    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }
}
