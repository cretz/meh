<?php
namespace Meh\Lua\Ast;

class IfStatement extends Node implements Statement
{
    /** @var Expression */
    public $expression;

    /** @var Block */
    public $block;

    /** @var ElseIfExpression[] */
    public $elseIfExpressions;

    /** @var Block|null */
    public $elseBlock;

    /**
     * @param Expression $expression
     * @param Block $block
     * @param ElseIfExpression[] $elseIfExpressions
     * @param Block $elseBlock
     */
    public function __construct(
        Expression $expression,
        Block $block,
        array $elseIfExpressions = array(),
        Block $elseBlock = null
    ) {
        $this->expression = $expression;
        $this->block = $block;
        $this->elseIfExpressions = $elseIfExpressions;
        $this->elseBlock = $elseBlock;
    }
}
