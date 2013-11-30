<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\Variable;

trait StmtStatic
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    /**
     * @param Context $ctx
     * @param (Statement|LastStatement)[]
     * @return Variable
     */
    public function initStaticVarTable(Context $ctx, array &$stmts)
    {
        $var = $ctx->bld->varName([
            'php',
            'globals',
            '__staticVar__' . $ctx->peekFunc()->decl->name
        ]);
        // Append if statement that lazily creates static var table
        $stmts[] = $ctx->bld->ifStmt(
            $ctx->bld->eq($var, $ctx->bld->nil()),
            [$ctx->bld->assign($var, $ctx->bld->table())]
        );
        // Return var
        return $var;
    }

    public function transpileStmtStatic(\PHPParser_Node_Stmt_Static $node, Context $ctx)
    {
        // Statements
        $stmts = [];
        // Init the table holding all the static vars for the function
        $staticTable = $this->initStaticVarTable($ctx, $stmts);
        // Statements for initialization of static vars and assigning to locals
        $initStmts = [];
        $localStmts = [];
        foreach ($node->vars as $var) {
            $initStmts[] = $ctx->bld->assign(
                $ctx->bld->varName([$staticTable, $var->name]),
                $var->default === null ? $this->phpNull() : $this->transpile($var->default, $ctx)
            );
            // We need it in the local variable list
            $localStmts[] = $ctx->bld->assign(
                $ctx->bld->varName(['ctx', $var->name]),
                $ctx->bld->varName([$staticTable, $var->name])
            );
        }
        // If first one is nil, then initialize them all
        $stmts[] = $ctx->bld->ifStmt(
            $ctx->bld->eq($ctx->bld->varName([$staticTable, $node->vars[0]->name]), $ctx->bld->nil()),
            $initStmts
        );
        $stmts = array_merge($stmts, $localStmts);
        return $ctx->bld->stmts($stmts);
    }
}
