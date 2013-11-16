<?php
namespace Meh\Compiler;

use Meh\Lua\Ast\FunctionDeclaration;
use Meh\Lua\Ast\LastStatement;
use Meh\Lua\Ast\LocalFunctionDeclaration;

class FunctionContext implements StatementContainer
{
    /** @var FunctionDeclaration */
    public $decl;

    public function __construct(FunctionDeclaration $decl)
    {
        $this->decl = $decl;
    }

    public function append($stmts)
    {
        if (!is_array($stmts)) $stmts = [$stmts];
        if ($stmts[count($stmts) - 1] instanceof LastStatement) {
            $this->decl->body->block->lastStatement = array_pop($stmts);
        }
        $this->decl->body->block->statements = array_merge($this->decl->body->block->statements, $stmts)
    }
}
