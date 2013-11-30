<?php
namespace Meh\Lua\Ast;

class FunctionCall extends Node implements Statement, PrefixExpression
{
    /** @var PrefixExpression */
    public $prefixExpression;

    /** @var ArgumentList */
    public $arguments;

    /** @var Name|null */
    public $name;

    /**
     * @param PrefixExpression $prefixExpression
     * @param ArgumentList $arguments
     * @param Name $name
     */
    function __construct(PrefixExpression $prefixExpression, ArgumentList $arguments, Name $name = null)
    {
        $this->prefixExpression = $prefixExpression;
        $this->arguments = $arguments;
        $this->name = $name;
    }
}
