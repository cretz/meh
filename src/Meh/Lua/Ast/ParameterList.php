<?php
namespace Meh\Lua\Ast;

class ParameterList
{
    /** @var NameList */
    public $names;

    /** @var VariableArguments|null */
    public $variableArguments;

    /**
     * @param NameList $names
     * @param VariableArguments $variableArguments
     */
    public function __construct(NameList $names, VariableArguments $variableArguments = null)
    {
        $this->names = $names;
        $this->variableArguments = $variableArguments;
    }
}
