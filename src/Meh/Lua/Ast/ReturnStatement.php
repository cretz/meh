<?php
namespace Meh\Lua\Ast;

class ReturnStatement implements LastStatement
{
    /** @var ExpressionList|null */
    public $expressions;

    /** @param ExpressionList $expressions */
    public function __construct(ExpressionList $expressions = null)
    {
        $this->expressions = $expressions;
    }
}
