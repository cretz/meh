<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtWhile
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtWhile(\PHPParser_Node_Stmt_While $node, Context $ctx)
    {
        $stmts = [];
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        return $ctx->bld->whileStmt($this->transpile($node->cond, $ctx), $stmts);
    }
}
