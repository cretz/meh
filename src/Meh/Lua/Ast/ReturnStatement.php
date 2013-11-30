<?php
namespace Meh\Lua\Ast;

class ReturnStatement extends Node
{
    /** @var ExpressionList|null */
    public $expressions;

    /** @param ExpressionList $expressions */
    public function __construct(ExpressionList $expressions = null)
    {
        $this->expressions = $expressions;
    }
}
