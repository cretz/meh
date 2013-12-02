<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\Variable;

trait StmtStatic
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtStatic(\PHPParser_Node_Stmt_Static $node, Context $ctx)
    {
        $ctx->peekFunc()->needsStaticTbl = true;
        // Statements for initialization of static vars and assigning to locals
        $initStmts = [];
        $localStmts = [];
        $funcPrefix = $ctx->peekFunc()->name . '___';
        foreach ($node->vars as $var) {
            $initStmts[] = $ctx->bld->assign(
                $ctx->bld->varName(['_staticTbl', $funcPrefix . $var->name]),
                $var->default === null ? $this->phpNull() : $this->transpile($var->default, $ctx)
            );
            // We need it in the local variable list
            $localStmts[] = $ctx->bld->assign(
                $ctx->bld->varName(['ctx', $var->name]),
                $ctx->bld->varName(['_staticTbl', $funcPrefix . $var->name])
            );
        }
        // If first one is nil, then initialize them all
        array_unshift(
            $localStmts,
            $ctx->bld->ifStmt(
                $ctx->bld->eq(
                    $ctx->bld->varName(['_staticTbl', $funcPrefix . $node->vars[0]->name]), $ctx->bld->nil()
                ),
                $initStmts
            )
        );
        return $ctx->bld->stmts($localStmts);
    }
}
