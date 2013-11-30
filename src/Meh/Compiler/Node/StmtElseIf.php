<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtElseIf
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtElseIf(\PHPParser_Node_Stmt_ElseIf $node, Context $ctx)
    {
        $stmts = [];
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        return $ctx->bld->elseIfExpr($this->transpile($node->cond, $ctx), $ctx->bld->block($stmts));
    }
}
