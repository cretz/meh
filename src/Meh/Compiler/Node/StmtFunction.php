<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtFunction
{
    public function transpileStmtFunction(\PHPParser_Node_Stmt_Function $node, Context $ctx)
    {
        $ctx->builder->funcDecl($node->name, [], )
    }
}
