<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprPostDec
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprPostDec(\PHPParser_Node_Expr_PostDec $node, Context $ctx)
    {
        return $ctx->bld->call($ctx->bld->varName(['php', 'postDec']), [$this->transpile($node->var, $ctx)]);
    }
}
