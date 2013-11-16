<?php
namespace Meh\Lua\Ast;

class VariableList
{
    /** @var Variable[] */
    public $variables;

    /** @param Variable[] $variables */
    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }
}
