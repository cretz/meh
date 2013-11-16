<?php
namespace Meh\Lua\Ast;

class VariableExpressioon implements Variable
{
    /** @var PrefixExpression */
    public $prefixExpression;

    /** @var Expression */
    public $expression;

    /**
     * @param PrefixExpression $prefixExpression
     * @param Expression $expression
     */
    public function __construct(PrefixExpression $prefixExpression, Expression $expression)
    {
        $this->prefixExpression = $prefixExpression;
        $this->expression = $expression;
    }
}
