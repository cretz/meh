<?php
namespace Meh\Lua\Ast;

class FunctionDeclaration extends Node implements Statement
{
    /** @var FunctionName */
    public $name;

    /** @var FunctionBody */
    public $body;

    /**
     * @param FunctionName $name
     * @param FunctionBody $body
     */
    public function __construct(FunctionName $name, FunctionBody $body)
    {
        $this->name = $name;
        $this->body = $body;
    }
}
