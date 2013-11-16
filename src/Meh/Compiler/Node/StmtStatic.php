<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\Variable;

trait StmtStatic
{
    /**
     * @param Context $ctx
     * @return Variable
     */
    public function initStaticVarTable(Context $ctx)
    {
        $var = $ctx->bld->varName([
            'php',
            'globals',
            '__staticVar__' . $ctx->peekFunc()->decl->name
        ]);
        // Append if statement that lazily creates static var table
        $stmt = $ctx->bld->ifStmt(
            $ctx->bld->eq($var, $ctx->bld->nil()),
            [$ctx->bld->assign($var, $ctx->bld->table())]
        );
        return $ctx->append($stmt);
        // Return var
        return $var;
    }

    public function transpileStmtStatic(\PHPParser_Node_Stmt_Static $node, Context $ctx)
    {
        // Init the table holding all the static vars for the function
        $staticTable = $this->initStaticVarTable($ctx);
        // Statements for initialization of static vars
        $stmts = [];
        foreach ($node->vars as $var) {
            $stmts[] = $ctx->bld->assign(
                $ctx->bld->name([$staticTable, $node->vars[0]->name]),
                $var->default === null ? $this->phpNull() : $this->phpVal($var->default)
            );
        }
        // If first one is nil, then initialize them all
        $init = $ctx->bld->ifStmt(
            $ctx->bld->eq($ctx->bld->name([$staticTable, $node->vars[0]->name]), $ctx->bld->nil()),
            $stmts
        );
        // Append init
        $this->ctx->append($init);
    }
}
