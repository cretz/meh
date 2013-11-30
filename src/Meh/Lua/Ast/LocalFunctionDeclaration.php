<?php
namespace Meh\Lua\Ast;

class LocalFunctionDeclaration extends Node implements Statement
{
    /** @var Name */
    public $name;

    /** @var FunctionBody */
    public $body;

    /**
     * @param Name $name
     * @param FunctionBody $body
     */
    public function __construct(Name $name, FunctionBody $body)
    {
        $this->name = $name;
        $this->body = $body;
    }
}
