<?php
namespace Meh\Lua\Ast;

class LocalAssignment implements Statement
{
    /** @var NameList */
    public $names;

    /** @var ExpressionList|null */
    public $expressions;

    /**
     * @param NameList $names
     * @param ExpressionList $expressions
     */
    public function __construct(NameList $names, ExpressionList $expressions = null)
    {
        $this->names = $names;
        $this->expressions = $expressions;
    }
}
