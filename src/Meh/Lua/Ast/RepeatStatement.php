<?php
namespace Meh\Lua\Ast;

class RepeatStatement implements Statement
{
    /** @var Block */
    public $block;

    /** @var Expression */
    public $expression;

    /**
     * @param Block $block
     * @param Expression $expression
     */
    public function __construct(Block $block, Expression $expression)
    {
        $this->block = $block;
        $this->expression = $expression;
    }
}
