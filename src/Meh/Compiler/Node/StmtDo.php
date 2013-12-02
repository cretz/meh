<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtDo
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtDo(\PHPParser_Node_Stmt_Do $node, Context $ctx)
    {
        $stmts = [];
        $ctx->pushLoop();
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        $loop = $ctx->popLoop();
        if ($loop->continueLabel !== null) $stmts[] = $ctx->bld->label($loop->continueLabel);
        $doStmt = $ctx->bld->repeatStatement($stmts, $ctx->phpIsFalse($this->transpile($node->cond, $ctx)));
        // Break statement means we need a statement list
        if ($loop->breakLabel === null) return $doStmt;
        return $ctx->bld->stmts([$doStmt, $ctx->bld->label($loop->breakLabel)]);
    }
}
