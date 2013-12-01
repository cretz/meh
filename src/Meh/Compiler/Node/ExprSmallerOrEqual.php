<?php
namespace Meh\Compiler\Node;

use Meh\Compiler\Context;

trait ExprSmallerOrEqual
{
    abstract public function transpile(\PHPParser_Node $node, Context $ctx);

    public function transpileExprSmallerOrEqual(\PHPParser_Node_Expr_SmallerOrEqual $node, Context $ctx)
    {
        return $ctx->phpLte($this->transpile($node->left, $ctx), $this->transpile($node->right, $ctx));
    }
}
