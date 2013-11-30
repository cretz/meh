<?php
namespace Meh\Lua\Ast;

class BinaryOperator extends Node
{
    /** @var string */
    public $operator;

    /** @param string $operator */
    public function __construct($operator)
    {
        $this->operator = $operator;
    }
}
