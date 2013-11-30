<?php
namespace Meh\Lua\Ast;

class String extends Node implements Expression, ArgumentList
{
    /** @var string */
    public $value;

    /** @param string $value */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
