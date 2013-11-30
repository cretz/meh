<?php
namespace Meh\Lua\Ast;

class ParameterList extends Node
{
    /** @var NameList|null */
    public $names;

    /** @var VariableArguments|null */
    public $variableArguments;

    /**
     * @param NameList $names
     * @param VariableArguments $variableArguments
     */
    public function __construct(NameList $names = null, VariableArguments $variableArguments = null)
    {
        $this->names = $names;
        $this->variableArguments = $variableArguments;
    }
}
