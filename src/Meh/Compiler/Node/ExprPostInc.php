<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprPostInc
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprPostInc(\PHPParser_Node_Expr_PostInc $node, Context $ctx)
    {
        return $ctx->bld->call($ctx->bld->varName(['php', 'postInc']), [$this->transpile($node->var, $ctx)]);
    }
}
