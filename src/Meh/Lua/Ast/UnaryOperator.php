<?php
namespace Meh\Lua\Ast;

class UnaryOperator extends Node
{
    /** @var string */
    public $operator;

    /** @param string $operator */
    public function __construct($operator)
    {
        $this->operator = $operator;
    }
}
