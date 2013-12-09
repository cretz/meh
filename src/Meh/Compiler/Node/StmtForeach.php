<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait StmtForeach
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtForeach(\PHPParser_Node_Stmt_Foreach $node, Context $ctx)
    {
        // Temp vars for the array and key
        $arrTmpVar = $ctx->peekVarCtx()->newLocalTmpVarName();
        $keyTmpVar = $ctx->peekVarCtx()->newLocalTmpVarName();
        // First, assign local array tmp var
        $allStmts = [
            $ctx->bld->localAssign($ctx->bld->nameList([$arrTmpVar]), $this->transpile($node->expr, $ctx))
        ];
        // Now, reset it
        $allStmts[] = $ctx->phpCall(['reset'], [$ctx->bld->varName($arrTmpVar)]);
        // Create key local outside loop
        $allStmts[] = $ctx->bld->localAssign($ctx->bld->nameList([$keyTmpVar]));
        // Create loop statements
        $loopStmts = [];
        // Assign key (that will tell us whether we're done)
        $loopStmts[] = $ctx->bld->assign(
            $ctx->bld->varName($keyTmpVar),
            $ctx->phpCall(['key'], [$ctx->bld->varName($arrTmpVar)])
        );
        // Now we can create the statements that will go inside the key-is-there if stmt
        // Beginning statements assign from tmp vars
        // First, if a key is there, assign that
        $ifStmts = [];
        if ($node->keyVar !== null) {
            $ifStmts[] = $ctx->bld->assign($this->transpile($node->keyVar, $ctx), $ctx->bld->varName($keyTmpVar));
        }
        // Now the current value
        $ifStmts[] = $ctx->bld->assign(
            $this->transpile($node->valueVar, $ctx),
            $ctx->phpCall(['current'], [$ctx->bld->varName($arrTmpVar)])
        );
        // Push loop
        $ctx->pushLoop();
        foreach ($node->stmts as $stmt) {
            $ifStmts[] = $this->transpile($stmt, $ctx);
        }
        // Pop loop
        $loop = $ctx->popLoop();
        // Put continue here if necessary
        if ($loop->continueLabel !== null) {
            $ifStmts[] = $ctx->bld->label($loop->continueLabel);
        }
        // Now the next
        $ifStmts[] = $ctx->phpCall(['next'], [$ctx->bld->varName($arrTmpVar)]);
        // Create if statement w/ contents
        $loopStmts[] = $ctx->bld->ifStmt(
            $ctx->bld->neq($ctx->bld->varName([$keyTmpVar, 'type']), $ctx->bld->string("null")),
            $ifStmts
        );
        // Now wrap it all in a repeat statement
        $allStmts[] = $ctx->bld->repeatStatement(
            $loopStmts,
            $ctx->bld->eq($ctx->bld->varName([$keyTmpVar, 'type']), $ctx->bld->string("null"))
        );
        // Also a break label if necessary
        if ($loop->breakLabel !== null) {
            $allStmts[] = $ctx->bld->label($loop->breakLabel);
        }
        return $ctx->bld->stmts($allStmts);
    }
}
