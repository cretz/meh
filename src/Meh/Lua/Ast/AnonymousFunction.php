<?php
namespace Meh\Lua\Ast;

class AnonymousFunction implements Expression
{
    /** @var FunctionBody */
    public $body;

    /** @param FunctionBody $body */
    public function __construct(FunctionBody $body)
    {
        $this->body = $body;
    }
}
