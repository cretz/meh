<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprAssign
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprAssign(\PHPParser_Node_Expr_Assign $node, Context $ctx)
    {
        return $ctx->bld->call(
            $ctx->bld->varName(['php', 'assign']),
            [$this->transpile($node->var, $ctx), $this->transpile($node->expr, $ctx)]
        );
    }
}
