<?php
namespace Meh\Lua\Ast;

class VariableName extends Node implements Variable
{
    /** @var PrefixExpression */
    public $prefixExpression;

    /** @var Name */
    public $name;

    /**
     * @param PrefixExpression $prefixExpression
     * @param Name $name
     */
    public function __construct(PrefixExpression $prefixExpression, Name $name)
    {
        $this->prefixExpression = $prefixExpression;
        $this->name = $name;
    }
}
