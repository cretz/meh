<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtWhile
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtWhile(\PHPParser_Node_Stmt_While $node, Context $ctx)
    {
        $stmts = [];
        $ctx->pushLoop();
        foreach ($node->stmts as $stmt) {
            $stmts[] = $this->transpile($stmt, $ctx);
        }
        // Pop loop
        $loop = $ctx->popLoop();
        // TODO: Handle special breaks/continues
        if ($loop->breakLabel !== null || $loop->continueLabel !== null) {
            throw new MehException('Special break/continue not supported');
        }
        return $ctx->bld->whileStmt(
            $ctx->phpIsTrue($this->transpile($node->cond, $ctx)),
            $stmts
        );
    }
}
