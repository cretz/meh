<?php
namespace Meh\Lua\Ast;

class KeywordLiteral implements Expression
{
    /** @var bool|null */
    public $value;

    /** @param bool|null $value */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
