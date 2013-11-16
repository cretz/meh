<?php
namespace Meh\Lua\Ast;

class WhileStatement implements Statement
{
    /** @var Expression */
    public $expression;

    /** @var Block */
    public $block;

    /**
     * @param Expression $expression
     * @param Block $block
     */
    public function __construct(Expression $expression, Block $block)
    {
        $this->expression = $expression;
        $this->block = $block;
    }
}
