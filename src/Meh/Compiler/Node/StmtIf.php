<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtIf
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtIf(\PHPParser_Node_Stmt_If $node, Context $ctx)
    {
        $elseIfs = [];
        foreach ($node->elseifs as $elseIf) {
            $elseIfs[] = $this->transpile($elseIf, $ctx);
        }
        $stmts = [];
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        return $ctx->bld->ifStmt(
            $ctx->phpIsTrue($this->transpile($node->cond, $ctx)),
            $stmts,
            $elseIfs,
            $node->else === null ? null : $this->transpile($node->else, $ctx)
        );
    }
}
