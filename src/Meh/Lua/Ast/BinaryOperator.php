<?php
namespace Meh\Lua\Ast;

class BinaryOperator
{
    /** @var string */
    public $operator;

    /** @param string $operator */
    public function __construct($operator)
    {
        $this->operator = $operator;
    }
}
