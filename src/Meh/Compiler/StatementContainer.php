<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\LastStatement;
use Meh\Lua\Ast\Statement;

interface StatementContainer
{
    /** @param Statement|LastStatement|(Statement|LastStatement)[] $stmts */
    public function append($stmts);
}
