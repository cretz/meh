<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprBooleanOr
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprBooleanOr(\PHPParser_Node_Expr_BooleanOr $node, Context $ctx)
    {
        return $ctx->bld->orExpr($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
