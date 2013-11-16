<?php
namespace Meh\Lua\Ast;

class Number implements Expression
{
    /** @var int|double */
    public $value;

    /** @param int|double $value */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
