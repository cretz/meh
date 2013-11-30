<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;
use Meh\Lua\Ast\VariableArguments;
use Meh\MehException;

trait StmtGlobal
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileStmtGlobal(\PHPParser_Node_Stmt_Global $node, Context $ctx)
    {
        $ctx->peekFunc()->needsGlobalCtx = true;
        // Setup all variables (better be variables)
        $stmts = [];
        foreach ($node->vars as $var) {
            if (!($var instanceof \PHPParser_Node_Expr_Variable)) throw new MehException('Unrecognized var');
            $stmts[] = $ctx->bld->assign(
                $this->transpile($var, $ctx),
                $ctx->bld->varName(['_globalCtx', $var->name])
            );
        }
        return $ctx->bld->stmts($stmts);
    }
}
