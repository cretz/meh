<?php
namespace Meh\Lua\Ast;

class Assignment extends Node implements Statement
{
    /** @var VariableList */
    public $variables;

    /** @var ExpressionList */
    public $expressions;

    /**
     * @param VariableList $variables
     * @param ExpressionList $expressions
     */
    public function __construct(VariableList $variables, ExpressionList $expressions)
    {
        $this->variables = $variables;
        $this->expressions = $expressions;
    }
}
