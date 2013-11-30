<?php
namespace Meh\Lua\Ast;

class AnonymousFunction extends Node implements Expression
{
    /** @var FunctionBody */
    public $body;

    /** @param FunctionBody $body */
    public function __construct(FunctionBody $body)
    {
        $this->body = $body;
    }
}
