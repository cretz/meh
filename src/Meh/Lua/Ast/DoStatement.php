<?php
namespace Meh\Lua\Ast;

class DoStatement extends Node implements Statement
{
    /** @var Block */
    public $block;

    /** @param Block $block */
    public function __construct(Block $block)
    {
        $this->block = $block;
    }
}
