<?php
namespace Meh\Lua\Ast;

class ForInStatement extends Node implements Statement
{
    /** @var NameList */
    public $names;

    /** @var ExpressionList */
    public $expressions;

    /** @var Block */
    public $block;

    /**
     * @param NameList $names
     * @param ExpressionList $expressions
     * @param Block $block
     */
    public function __construct(NameList $names, ExpressionList $expressions, Block $block)
    {
        $this->names = $names;
        $this->expressions = $expressions;
        $this->block = $block;
    }
}
