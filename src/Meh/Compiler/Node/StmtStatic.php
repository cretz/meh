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
        // Statements for initialization of static vars
        $initStmts = [];
        foreach ($node->vars as $var) {
            $initStmts[] = $ctx->bld->assign(
                $ctx->bld->name([$staticTable, $node->vars[0]->name]),
                $var->default === null ? $this->phpNull() : $this->transpile($var->default)
            );
        }
        // If first one is nil, then initialize them all
        $stmts[] = $ctx->bld->ifStmt(
            $ctx->bld->eq($ctx->bld->name([$staticTable, $node->vars[0]->name]), $ctx->bld->nil()),
            $initStmts
        );
        return $ctx->bld->block($stmts);
    }
}
