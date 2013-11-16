<?php
namespace Meh\Lua\Ast;

class ExpressionList
{
    /** @var Expression[] */
    public $expressions;

    /** @param Expression[] $expressions */
    public function __construct(array $expressions)
    {
        $this->expressions = $expressions;
    }
}
