<?php
namespace Meh\Lua\Ast;

class StatementList extends Node implements Statement
{
    /** @var Statement[] */
    public $statements;

    /** @param Statement[] $statements */
    public function __construct(array $statements)
    {
        $this->statements = $statements;
    }
}
