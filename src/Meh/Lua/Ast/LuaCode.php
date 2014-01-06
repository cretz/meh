<?php
namespace Meh\Lua\Ast;

class LuaCode extends Node implements Expression
{
    /** @var string */
    public $value;

    /** @param string $value */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
