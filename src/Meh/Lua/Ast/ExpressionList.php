<?php
namespace Meh\Lua\Ast;

class ExpressionList extends Node
{
    /** @var Expression[] */
    public $expressions;

    /** @param Expression[] $expressions */
    public function __construct(array $expressions)
    {
        $this->expressions = $expressions;
    }
}
