<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtElse
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtElse(\PHPParser_Node_Stmt_Else $node, Context $ctx)
    {
        $stmts = [];
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        return $ctx->bld->block($stmts);
    }
}
