<?php
namespace Meh\Lua\Ast;

class FunctionBody extends Node
{
    /** @var ParameterList */
    public $parameters;

    /** @var Chunk */
    public $block;

    /**
     * @param ParameterList $parameters
     * @param Block $block
     */
    public function __construct(ParameterList $parameters, Block $block)
    {
        $this->parameters = $parameters;
        $this->block = $block;
    }
}
