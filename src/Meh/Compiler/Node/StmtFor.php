<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtFor
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtFor(\PHPParser_Node_Stmt_For $node, Context $ctx)
    {
        $initStmts = [];
        // Init stmts
        foreach ($node->init as $stmt) {
            $initStmts[] = $this->transpile($stmt, $ctx);
        }
        // Conditionals
        $cond = null;
        foreach ($node->cond as $expr) {
            $condExpr = $ctx->phpIsTrue($this->transpile($expr, $ctx));
            if ($cond === null) $cond = $condExpr;
            else $cond = $ctx->bld->andExpr($cond, $condExpr);
        }
        if ($cond === null) $cond = $ctx->bld->trueExpr();
        // All the statements in the loop
        $stmts = [];
        $ctx->pushLoop();
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        $loop = $ctx->popLoop();
        // Put continue here if necessary
        if ($loop->continueLabel !== null) {
            $stmts[] = $ctx->bld->label($loop->continueLabel);
        }
        // Add the loop statements at the end
        foreach ($node->loop as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        // Add while to end of init statements
        $initStmts[] = $ctx->bld->whileStmt($cond, $stmts);
        // Also a break label if necessary
        if ($loop->breakLabel !== null) {
            $initStmts[] = $ctx->bld->label($loop->breakLabel);
        }
        return $ctx->bld->stmts($initStmts);
    }
}
