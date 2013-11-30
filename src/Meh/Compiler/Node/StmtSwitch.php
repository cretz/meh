<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\MehException;

trait StmtSwitch
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtSwitch(\PHPParser_Node_Stmt_Switch $node, Context $ctx)
    {
        // If all cases (except the last) contain a return or break at the end
        //  we can take the if-else route instead of arbitrary labels and gotos
        $noBreak = false;
        for ($i = 0; $i < count($node->cases) - 1; $i++) {
            $case = $node->cases[$i];
            // Cases next to each other can go into the same conditional
            if (empty($case->stmts)) continue;
            $lastStmt = $case->stmts[count($case->stmts) - 1];
            if (!($lastStmt instanceof \PHPParser_Node_Stmt_Break)
                && !($lastStmt instanceof \PHPParser_Node_Stmt_Return)
            ) {
                $noBreak = true;
                break;
            }
        }
        // Only if-else supported right now
        if ($noBreak) throw new MehException('Unsupported fall-through');
        return $this->createSwitchIfElse($node, $ctx);
    }

    public function createSwitchIfElse(\PHPParser_Node_Stmt_Switch $node, Context $ctx)
    {
        // Get LHS of conditional and assign to local tmp var
        $tmpVar = $ctx->peekVarCtx()->newLocalTmpVarName();
        $tmpVarStmt = $ctx->bld->localAssign(
            $ctx->bld->nameList([$tmpVar]),
            [$this->transpile($node->cond, $ctx)]
        );
        $left = $ctx->bld->varName($tmpVar);
        // Go through each case, making it part of if/else chain
        // This is the set of do-nothing fall-throughs
        $conditionalsToAddToNext = [];
        $ifExpr = null;
        $ifStmts = [];
        $elseIfExprs = [];
        $elseBlock = null;
        foreach ($node->cases as $case) {
            // No condition? It's just the else block
            $cond = null;
            if ($case->cond !== null) {
                $conditionalsToAddToNext[] = $case->cond;
                // No statements? Do nothing, just append to conditionals to add next
                if (empty($case->stmts)) continue;
                // Setup all conditionals
                foreach ($conditionalsToAddToNext as $conditionalToAdd) {
                    if ($cond == null) $cond = $ctx->phpEq($left, $this->transpile($conditionalToAdd, $ctx));
                    else {
                        $cond = $ctx->bld->andExpr(
                            $cond,
                            $ctx->phpEq($left, $this->transpile($conditionalToAdd, $ctx))
                        );
                    }
                }
            } elseif (empty($case->stmts)) continue;
            // Clear out conditional array
            $conditionalsToAddToNext = [];
            // Now the block
            $stmts = [];
            foreach ($case->stmts as $stmt) {
                // Skip breaks
                if (!($stmt instanceof \PHPParser_Node_Stmt_Break)) $stmts[] = $this->transpile($stmt, $ctx);
            }
            // First is the if, others are elseif/else
            if ($ifExpr === null) {
                // An empty condition? That's a strange default-only switch, but so be it
                if ($cond == null) $ifExpr = $ctx->bld->trueExpr();
                else $ifExpr = $cond;
                $ifStmts = $stmts;
            } elseif ($cond !== null) {
                $elseIfExprs[] = $ctx->bld->elseIfExpr($cond, $ctx->bld->block($stmts));
            } else {
                $elseBlock = $ctx->bld->block($stmts);
            }
        }
        // Return if statement with the tmp assign
        return $ctx->bld->stmts([
            $tmpVarStmt,
            $ctx->bld->ifStmt($ifExpr, $ifStmts, $elseIfExprs, $elseBlock)
        ]);
    }
}
